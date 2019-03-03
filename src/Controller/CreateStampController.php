<?php

namespace App\Controller;

use App\Entity\HandleShape;
use App\Entity\StampQuote;
use App\Entity\StampQuoteSketch;
use App\Form\CreateStamp\ContactDetailsFormType;
use App\Form\CreateStamp\HandleSelection;
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
use App\Service\UploaderHelper;
use Couchbase\Document;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateStampController extends AbstractController
{
    /**
     * @Route("/create-your-stamp", name="create_stamp")
     */
    public function index()
    {
        $breadcrumbs = ['Create Your Stamp'];

        return $this->render('create_stamp/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/create-your-stamp/contact-details", name="create_stamp_contact_details_new")
     */
    public function step5(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Create Your Stamp', 'Contact Details'];

        $form = $this->createForm(ContactDetailsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $unique = date('dmYHis') . rand(1000, 9999);
            $enquiry = new StampQuote();
            $enquiry->setStatus('CREATED');
            $enquiry->setName($data['fullName']);
            $enquiry->setEmail($data['email']);
            $enquiry->setShippingCountry($data['shippingCountry']);
            $enquiry->setIdentifier($unique);
            $manager->persist($enquiry);
            $manager->flush();
            $this->addFlash('success', 'Thank you. This information will be used to contact you.');
            return $this->redirectToRoute('create_stamp_items', ['identifier' => $enquiry->getIdentifier()]);
        }

        return $this->render('create_stamp/start.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create-your-stamp/{identifier}/contact-details", name="create_stamp_contact_details")
     */
    public function showContactDetails(StampQuote $enquiry)
    {
        $breadcrumbs = ['Create Your Stamp', 'Contact Details'];

        return $this->render('create_stamp/show_contact_details.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry
        ]);

    }

    /**
     * @Route("/create-your-stamp/{identifier}/items", name="create_stamp_items")
     */
    public function showItems(StampQuote $enquiry)
    {
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
            $fileName = $uploaderHelper->uploadFile($file,$this->getParameter('sketch_dir'));

            $fileExtension = $file->guessExtension();
            $fileOriginal = $file->getClientOriginalName();

            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';


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
        $breadcrumbs = ['Create Your Stamp', 'Summary'];

        $form = $this->createForm(SummaryCommentFormType::class, $enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Thank you for your enquiry. Your enquiry has been sent to CocktailBrandalism.<br /> We will prepare your quote as soon as possible and email it to '.$enquiry->getEmail().' or we might contact you to clarify your requirement.');

            // TODO
            // tu musi prist email pre nas a pre zakaznika

            $enquiry->setStatus('SENT TO US');
            $manager->persist($enquiry);
            $manager->flush();

            return $this->redirectToRoute('welcome');
        }

        return $this->render('create_stamp/show_summary.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'enquiry' => $enquiry,
            'form' => $form->createView()
        ]);

    }

//    /**
//     * @Route("/create-your-stamp/{identifier}/logo-and-item", name="create_stamp_step1")
//     */
//    public function step1(Request $request, StampQuote $enquiry, ObjectManager $manager)
//    {
//        $breadcrumbs = ['Create Your Stamp','Logo & Item'];
//
//        $form = $this->createForm(LogoItemDetailsFormType::class);
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//            $data = $form->getData();
//
//            /** @var UploadedFile $file */
//            $file = $form->get('file')->getData();
//            $fileExtension = $file->guessExtension();
//            $fileName = md5(uniqid()) . '.' . $fileExtension;
//            $fileOriginal = $file->getClientOriginalName();
//
//            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';
//
//            // moves the file to the directory where tags are stored
//            $file->move(
//                $this->getParameter('sketch_dir'),
//                $fileName
//            );
//
//            $sketch = new StampQuoteSketch();
//            $sketch->setStampQuote($enquiry);
//            $sketch->setFile($fileName);
//            $sketch->setOriginalFile($fileOriginal);
//            $sketch->setFileSize($fileSize);
//            $sketch->setExtension($fileExtension);
//            $sketch->setStampType($data['stampType']);
//            $sketch->setQty($data['qty']);
//            $manager->persist($sketch);
//
//            $enquiry->setStatus('STEP1');
//            $manager->persist($enquiry);
//
//            $manager->flush();
//
////            if(ice stamp or wax stamp then to) {
////                stamp shape
////            }else{
////                stamp size
////            }
//
//            return $this->redirectToRoute('create_stamp_stamp_shape', ['identifier' => $enquiry->getId()]);
//
//        }
//
//        return $this->render('create_stamp/step1.html.twig', [
//            'title' => $breadcrumbs[0],
//            'breadcrumbs' => $breadcrumbs,
//            'form' => $form->createView(),
//            'enquiry' => $enquiry
//        ]);
//    }

    /**
     * @Route("/create-your-stamp/{identifier}/stamp-shape", name="create_stamp_stamp_shape")
     */
    public function step2(Request $request, StampQuote $enquiry, ObjectManager $manager)
    {
        $breadcrumbs = ['Create Your Stamp', 'Stamp Shape'];

        $form = $this->createForm(StampShapeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('create_stamp/step2.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-your-stamp/stamp-size", name="create_stamp_step3")
     */
    public function step3(Request $request)
    {
        $breadcrumbs = ['Create Your Stamp', 'Stamp Size'];

        $form = $this->createForm(StampSizeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('create_stamp/step3.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-your-stamp/handle-selection", name="create_stamp_step4")
     */
    public function step4(Request $request)
    {
        $breadcrumbs = ['Create Your Stamp', 'Handle Selection'];

        $form = $this->createForm(HandleSelection::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('create_stamp/step4.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-your-stamp/stamp-complete", name="create_stamp_step6")
     */
    public function step6()
    {
        $breadcrumbs = ['Create Your Stamp', 'Completed'];

        return $this->render('create_stamp/step6.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs
        ]);
    }

}
