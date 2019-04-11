<?php

namespace App\Controller;

use App\Entity\HandleShape;
use App\Entity\StampQuote;
use App\Entity\StampQuoteSketch;
use App\Form\CreateStamp\BrandingIronCustomFormType;
use App\Form\CreateStamp\ContactDetailsFormType;
use App\Form\CreateStamp\HandleSelection;
use App\Form\CreateStamp\HeatStampCustomFormType;
use App\Form\CreateStamp\IceStampCustomFormType;
use App\Form\CreateStamp\LogoItemDetailsFormType;
use App\Form\CreateStamp\StampShapeFormType;
use App\Form\CreateStamp\StampSizeFormType;
use App\Form\CreateStamp\SummaryCommentFormType;
use App\Repository\HandleColorRepository;
use App\Repository\HandleShapeRepository;
use App\Repository\StampQuoteRepository;
use App\Repository\StampShapeRepository;
use App\Repository\StampTypeRepository;
use App\Service\CreateStamp;
use App\Service\Mailer;
use App\Service\UploaderHelper;
use Couchbase\Document;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateStampController extends AbstractController
{

    /**
     * @Route("/create-your-stamp", name="create_stamp")
     */
    public function index(CreateStamp $stamp)
    {
        $breadcrumbs = ['Create Your Stamp'];
        $quote = $stamp->getQuote();
        if($quote){
            return $this->redirectToRoute('create_stamp_items', [
                'identifier' => $quote->getIdentifier()
            ]);
        }

        return $this->render('create_stamp/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/create-your-stamp/contact-details", name="create_stamp_contact_details_new")
     */
    public function contactDetails(Request $request, ObjectManager $manager, CreateStamp $createStamp)
    {

        $breadcrumbs = ['Create Your Stamp', 'Contact Details'];

        $form = $this->createForm(ContactDetailsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $unique = date('dmYHis') . rand(1000, 9999);
            $enquiry = new StampQuote();
            $enquiry->setStatus('CREATED');
            $enquiry->setName($data['name']);
            $enquiry->setEmail($data['email']);
            $enquiry->setShippingCountry($data['shippingCountry']);
            $enquiry->setIdentifier($unique);
            $manager->persist($enquiry);
            $manager->flush();
            $this->addFlash('success', 'Thank you. This information will be used to contact you.');
            $createStamp->createCustomStampSession($enquiry->getId());
            return $this->redirectToRoute('create_stamp_items', ['identifier' => $enquiry->getIdentifier()]);
        }

        return $this->render('create_stamp/start.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create-your-stamp/{identifier}/contact-details", name="create_stamp_contact_details_show")
     */
    public function showContactDetails(StampQuote $enquiry)
    {
        if($enquiry->getStatus() == "SENT TO US"){
            $this->addFlash('warning', 'This enquiry cannot be modified.');
            return $this->redirectToRoute('welcome');
        }
        $breadcrumbs = ['Create Your Stamp', 'Contact Details'];

        return $this->render('create_stamp/show_contact_details.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/contact-details/edit", name="create_stamp_contact_details_edit")
     */
    public function editContactDetails(StampQuote $enquiry, Request $request, ObjectManager $manager)
    {
        if($enquiry->getStatus() == "SENT TO US"){
            $this->addFlash('warning', 'This enquiry cannot be modified.');
            return $this->redirectToRoute('welcome');
        }
        $breadcrumbs = ['Create Your Stamp', 'Contact Details'];

        $form = $this->createForm(ContactDetailsFormType::class, $enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($enquiry);
            $manager->flush();

            $this->addFlash('success', 'Contact details have been updated.');
            return $this->redirectToRoute('create_stamp_contact_details_show', ['identifier' => $enquiry->getIdentifier()]);
        }

        return $this->render('create_stamp/edit_contact_details.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/items", name="create_stamp_items")
     */
    public function showItems(StampQuote $enquiry)
    {
        if($enquiry->getStatus() == "SENT TO US"){
            $this->addFlash('warning', 'This enquiry cannot be modified.');
            return $this->redirectToRoute('welcome');
        }
        $breadcrumbs = ['Create Your Stamp', 'Items'];

        return $this->render('create_stamp/show_items.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/items/ice-stamp", name="create_stamp_items_ice_stamp")
     */
    public function createIceStamp(Request $request, StampQuote $enquiry, HandleShapeRepository $shapeRepository, HandleColorRepository $colorRepository, ObjectManager $manager, StampShapeRepository $stampShapeRepository, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper)
    {
        if($enquiry->getStatus() == "SENT TO US"){
            $this->addFlash('warning', 'This enquiry cannot be modified.');
            return $this->redirectToRoute('welcome');
        }

        $breadcrumbs = ['Create Your Stamp', 'Items', 'Ice Stamp'];

        $handleShapes = $shapeRepository->findAll();
        $handleColors = $colorRepository->findAll();

        $form = $this->createForm(IceStampCustomFormType::class);
        $form->handleRequest($request);

//size/ quantity / sphere


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
//            dd($data);
            $handleShape = $shapeRepository->findOneBy([
                'value' => $data['handle_shape']
            ]);

            $handleColor = $colorRepository->findOneBy([
                'value' => $data['handle_color']
            ]);

            $stampShape = $stampShapeRepository->findOneBy([
                'value' => $data['stamp_shape']
            ]);

            $stampType = $stampTypeRepository->findOneBy([
                'value' => 'Ice Stamp'
            ]);


            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $fileExtension = $file->guessExtension();
            $fileOriginal = $file->getClientOriginalName();

            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';
            $fileName = $uploaderHelper->uploadFile($file, $this->getParameter('sketch_dir'));

            $sketch = new StampQuoteSketch();
            $sketch->setStampQuote($enquiry);
            $sketch->setFile($fileName);
            $sketch->setOriginalFile($fileOriginal);
            $sketch->setFileSize($fileSize);
            $sketch->setExtension($fileExtension);
            $sketch->setHandleShape($handleShape);
            $sketch->setHandleColor($handleColor);
            $sketch->setStampShape($stampShape);
            $sketch->setStampType($stampType);
            $sketch->setQty($data['qty']);
            $sketch->setIsSphere($data['is_sphere']);

            if ($data['is_sphere']) {
                $sketch->setSphereDiameter($data['diameterIceBall']);
            } else {
                switch ($data['stamp_shape']) {
                    case "Square":
                        $sketch->setSizeSideA($data['sizeSquare']);
                        break;
                    case "Rectangle":
                        $sketch->setSizeSideA($data['sizeRectangleA']);
                        $sketch->setSizeSideB($data['sizeRectangleB']);
                        break;
                    case "Circle":
                        $sketch->setSizeDiameter($data['sizeCircle']);
                        break;
                    case "Ellipse":
                        $sketch->setSizeSideA($data['sizeEllipseA']);
                        $sketch->setSizeSideB($data['sizeEllipseB']);
                        break;
                    case "Custom":
                        $sketch->setSizeCustomNote($data['customSizeNote']);
                        break;
                }
            }

            $sketch->setSizeNote($data['size']);

            $manager->persist($sketch);


            $manager->flush();

            $this->addFlash('success', 'Ice Stamp has been successfully added.');
            return $this->redirectToRoute('create_stamp_summary', ['identifier' => $enquiry->getIdentifier()]);
        }


        return $this->render('create_stamp/items/icestamp.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'handleShapes' => $handleShapes,
            'handleColors' => $handleColors,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/items/heat-stamp", name="create_stamp_items_heat_stamp")
     */
    public function createHeatStamp(Request $request, StampQuote $enquiry, ObjectManager $manager, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper)
    {
        $breadcrumbs = ['Create Your Stamp', 'Items', 'Heat Stamp'];

        $form = $this->createForm(HeatStampCustomFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $stampType = $stampTypeRepository->findOneBy([
                'value' => 'Heat Stamp'
            ]);

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $fileExtension = $file->guessExtension();
            $fileOriginal = $file->getClientOriginalName();

            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';
            $fileName = $uploaderHelper->uploadFile($file, $this->getParameter('sketch_dir'));

            $sketch = new StampQuoteSketch();
            $sketch->setStampQuote($enquiry);
            $sketch->setFile($fileName);
            $sketch->setOriginalFile($fileOriginal);
            $sketch->setFileSize($fileSize);
            $sketch->setExtension($fileExtension);
            $sketch->setStampType($stampType);
            $sketch->setQty($data['qty']);
            $sketch->setSizeCustomNote($data['customSizeNote']);
            $sketch->setSizeNote($data['customSizeNote']);
            $sketch->setComment($data['comment']);
            $manager->persist($sketch);
            $manager->flush();

            $this->addFlash('success', 'Branding Iron has been successfully added.');
            return $this->redirectToRoute('create_stamp_summary', ['identifier' => $enquiry->getIdentifier()]);

        }
        return $this->render('create_stamp/items/heatstamp.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-your-stamp/{identifier}/items/branding-iron", name="create_stamp_items_branding_iron")
     */
    public function createBrandingIron(Request $request, StampQuote $enquiry, ObjectManager $manager, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper, CreateStamp $stamp)
    {
        $breadcrumbs = ['Create Your Stamp', 'Items', 'Branding Iron'];

        $form = $this->createForm(BrandingIronCustomFormType::class);
        $form->handleRequest($request);

        $stamp->createCustomStampSession(null);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $stampType = $stampTypeRepository->findOneBy([
                'value' => 'Branding Iron'
            ]);

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $fileExtension = $file->guessExtension();
            $fileOriginal = $file->getClientOriginalName();

            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';
            $fileName = $uploaderHelper->uploadFile($file, $this->getParameter('sketch_dir'));

            $sketch = new StampQuoteSketch();
            $sketch->setStampQuote($enquiry);
            $sketch->setFile($fileName);
            $sketch->setOriginalFile($fileOriginal);
            $sketch->setFileSize($fileSize);
            $sketch->setExtension($fileExtension);
            $sketch->setStampType($stampType);
            $sketch->setQty($data['qty']);
            $sketch->setSizeCustomNote($data['sizeOptions']);
            $sketch->setSizeNote($data['sizeOptions']. ', Rack: '.$data['alkRackOption']);
            $sketch->setComment($data['comment']);
            $manager->persist($sketch);
            $manager->flush();

            $this->addFlash('success', 'Branding Iron has been successfully added.');
            return $this->redirectToRoute('create_stamp_summary', ['identifier' => $enquiry->getIdentifier()]);

        }
        return $this->render('create_stamp/items/brandingiron.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-your-stamp/sketch/delete/{id}", name="create_stamp_items_delete")
     */
    public function deleteSketch(StampQuoteSketch $sketch, ObjectManager $manager, StampQuoteRepository $stampQuoteRepository)
    {

        $identifier = $sketch->getStampQuote()->getIdentifier();
        $manager->remove($sketch);
        $manager->flush();

        $this->addFlash('success', 'Sketch has been removed. Thank you.');

        $stampQuote = $stampQuoteRepository->findOneBy([
            'identifier' => $identifier
        ]);

        if (count($stampQuote->getStampQuoteSketches()) > 0) {
            return $this->redirectToRoute('create_stamp_summary', [
                'identifier' => $identifier
            ]);
        } else {
            return $this->redirectToRoute('create_stamp_items', [
                'identifier' => $identifier
            ]);
        };
    }


    /**
     * @Route("/create-your-stamp/{identifier}/summary", name="create_stamp_summary")
     */
    public function showSummary(Request $request, StampQuote $enquiry, ObjectManager $manager)
    {
        if($enquiry->getStatus() == "SENT TO US"){
            $this->addFlash('warning', 'This enquiry cannot be modified.');
            return $this->redirectToRoute('welcome');
        }
        $breadcrumbs = ['Create Your Stamp', 'Summary'];

        $form = $this->createForm(SummaryCommentFormType::class, $enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($enquiry);
            $manager->flush();
            $this->addFlash('success', 'Additional comment has been saved.');
        }

        return $this->render('create_stamp/show_summary.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/send", name="create_stamp_send_enquiry")
     */
    public function sendEnquiry(StampQuote $enquiry, ObjectManager $manager, Mailer $mailer)
    {
        $enquiry->setStatus('SENT TO US');
        $enquiry->setUpdatedAt(new \DateTime('now'));
        $manager->persist($enquiry);
        $manager->flush();

        $mailer->sendEnquiry($enquiry);


        $this->addFlash('success', 'Thank you for your enquiry. Your enquiry has been sent to CocktailBrandalism.<br /> We will prepare your quote as soon as possible and email it to ' . $enquiry->getEmail() . ' or we might contact you to clarify your requirement.');
        return $this->redirectToRoute('welcome');
    }


}
