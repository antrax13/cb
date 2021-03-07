<?php

namespace App\Controller;

use App\Entity\BrandSketch;
use App\Entity\Customer;
use App\Entity\HandleShape;
use App\Entity\Quote;
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
use App\Repository\CustomerRepository;
use App\Repository\HandleColorRepository;
use App\Repository\HandleShapeRepository;
use App\Repository\StampQuoteRepository;
use App\Repository\StampShapeRepository;
use App\Repository\StampTypeRepository;
use App\Service\CreateStamp;
use App\Service\Mailer;
use App\Service\UploaderHelper;
use Couchbase\Document;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateStampController extends AbstractController
{

    /**
     * @Route("/create-your-stamp", name="create_stamp")
     */
    public function index(CreateStamp $stamp)
    {
        $breadcrumbs = [
            [
                'name' => 'Create Your Stamp',
                'url' => $this->generateUrl('create_stamp')
            ]
        ];

        // if session is created we want to redirect
        $quote = $stamp->getQuote();
        if($quote){
            return $this->redirectToRoute('create_stamp_items', [
                'identifier' => $quote->getIdentifier()
            ]);
        }

        return $this->render('create_stamp/index.html.twig', [
            'title' => $breadcrumbs[0]['name'],
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * @Route("/create-your-stamp/contact-details", name="create_stamp_contact_details_new")
     */
    public function contactDetails(Request $request, EntityManagerInterface $manager, CreateStamp $createStamp)
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
        $now = new \DateTime();
        if(in_array($enquiry->getStatus(), [
                $enquiry::STATUS_SEND_TO_US,
                $enquiry::STATUS_QUOTE_STARTED,
                $enquiry::STATUS_CLOSED
            ]) || ($enquiry->getStatus() == $enquiry::STATUS_REMINDER_ISSUED && $enquiry->getUpdatedAt()->modify('+2 weeks') < $now )){
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
    public function editContactDetails(StampQuote $enquiry, Request $request, EntityManagerInterface $manager)
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
        $now = new \DateTime();
        if(in_array($enquiry->getStatus(), [
            $enquiry::STATUS_SEND_TO_US,
            $enquiry::STATUS_QUOTE_STARTED,
            $enquiry::STATUS_CLOSED
        ]) || ($enquiry->getStatus() == $enquiry::STATUS_REMINDER_ISSUED && $enquiry->getUpdatedAt()->modify('+2 weeks') < $now )){
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
    public function createIceStamp(Request $request, StampQuote $enquiry, HandleShapeRepository $shapeRepository, HandleColorRepository $colorRepository, EntityManagerInterface $manager, StampShapeRepository $stampShapeRepository, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper)
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
    public function createHeatStamp(Request $request, StampQuote $enquiry, EntityManagerInterface $manager, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper)
    {
        $breadcrumbs = ['Create Your Stamp', 'Items', 'Heat Stamp'];

        $form = $this->createForm(HeatStampCustomFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $stampType = $stampTypeRepository->findOneBy([
                'value' => 'Heat Stamp / Manual Brander'
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
    public function createBrandingIron(Request $request, StampQuote $enquiry, EntityManagerInterface $manager, StampTypeRepository $stampTypeRepository, UploaderHelper $uploaderHelper, CreateStamp $stamp)
    {
        $breadcrumbs = ['Create Your Stamp', 'Items', 'Branding Iron'];

        $form = $this->createForm(BrandingIronCustomFormType::class);
        $form->handleRequest($request);

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
    public function deleteSketch(StampQuoteSketch $sketch, EntityManagerInterface $manager, StampQuoteRepository $stampQuoteRepository)
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
    public function showSummary(Request $request, StampQuote $enquiry, EntityManagerInterface $manager)
    {
        $now = new \DateTime();
        if(in_array($enquiry->getStatus(), [
                $enquiry::STATUS_SEND_TO_US,
                $enquiry::STATUS_QUOTE_STARTED,
                $enquiry::STATUS_CLOSED
            ]) || ($enquiry->getStatus() == $enquiry::STATUS_REMINDER_ISSUED && $enquiry->getUpdatedAt()->modify('+2 weeks') < $now )){
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
    public function sendEnquiry(StampQuote $enquiry, EntityManagerInterface $manager, Mailer $mailer, CreateStamp $stamp)
    {
        // clear session
        $stamp->emptyStampSession();

        $enquiry->setStatus('SENT TO US');
        $enquiry->setUpdatedAt(new \DateTime('now'));
        $manager->persist($enquiry);
        $manager->flush();

        $mailer->sendEnquiry($enquiry, $this->getParameter('my_personal_email'));


        $this->addFlash('success', 'Thank you for your enquiry. Your enquiry has been sent to CocktailBrandalism.<br /> We will prepare your quote as soon as possible and email it to ' . $enquiry->getEmail() . ' or we might contact you to clarify your requirement.');
        return $this->redirectToRoute('welcome');
    }


    /**
     * @Route("/admin/custom-orders", name="admin_custom_orders")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminCustomStampsList(StampQuoteRepository $repository)
    {
        $title = 'Custom Online Stamps';
        $breadcrumbs = ['Admin', 'Custom Online Stamps'];

        $customs = $repository->findBy([],[
            'id' => 'DESC'
        ]);
        $array = [];
        foreach($customs as $custom){
            $array[] = [
                'id' => $custom->getId(),
                'name' => $custom->getName(),
                'email' => $custom->getEmail(),
                'country' => $custom->getShippingCountry()->getName(),
                'sketches' => count($custom->getStampQuoteSketches()),
                'status' => $custom->getStatus(),
                'updated_at' => $custom->getUpdatedAt()->format('Y-m-d: H:i:s'),
            ];
        }

        return $this->render('quote/custom_orders/index.html.twig', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'custom_quotes' => $array
        ]);
    }

    /**
     * @Route("/admin/custom-order/{id}", name="admin_custom_order_show")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminCustomStampsShow(StampQuote $stampQuote)
    {
        $title = 'Custom Online Stamps - Show';
        $breadcrumbs = ['Admin', 'Custom Online Stamps', 'Show', $stampQuote->getId()];

        return $this->render('quote/custom_orders/show.html.twig', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'custom' => $stampQuote
        ]);
    }

    /**
     * @Route("/admin/custom-order/{id}/add", name="admin_custom_order_create", methods="POST")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createQuote(StampQuote $stampQuote, EntityManagerInterface $manager, CustomerRepository $customerRepository)
    {
        dump($stampQuote->getStatus());
        exit;
        if($stampQuote->getStatus() == $stampQuote::STATUS_SEND_TO_US || $stampQuote->getStatus() == $stampQuote::STATUS_REMINDER_ISSUED) {
            $customer = $customerRepository->findOneBy([
                'email' => $stampQuote->getEmail()
            ]);
            // order of events matter
            if (!$customer) {
                $customer = new Customer();
                $customer->setName($stampQuote->getName());
                $customer->setEmail($stampQuote->getEmail());
                $manager->persist($customer);
            }

            $quote = new Quote();
            $quote->setCustomer($customer);
            $quote->setRequest('From online form');
            $quote->setAnswer('None');
            $quote->setShippingCountry($stampQuote->getShippingCountry());
            $quote->setStatus('Draft');
            $manager->persist($quote);

            if($stampQuote->getStampQuoteSketches()){
                foreach($stampQuote->getStampQuoteSketches() as $custom){
                    $sketch = new BrandSketch();
                    $sketch->setQuote($quote);
                    $sketch->setName($custom->getOriginalFile());
                    $sketch->setPrice(0.01);
                    $sketch->setQty($custom->getQty());
                    $sketch->setstampType($custom->getStampType());
                    $handle = 'N/A';
                    if($custom->getHandleShape()){
                        $handle = $custom->getHandleShape()->getValue();
                        if($custom->getHandleColor()){
                            $handle.=''.$custom->getHandleColor()->getValue();
                        }else{
                            $handle = 'N/A';
                        }
                    }

                    $sizes = [];
                    $sizes[] = 'Side A: '.$custom->getSizeSideA();
                    $sizes[] = 'Side B: '.$custom->getSizeSideB();
                    $sizes[] = 'Diameter: '.$custom->getSizeDiameter();
                    $sizes[] = 'Sphere: '.$custom->getSphereDiameter();
                    $sizes[] = 'Size Custom: '.$custom->getSizeCustomNote();
                    $sizes[] = 'Size Note: '.$custom->getSizeNote();

                    $sketch->setHandle($handle);
                    $sketch->setWeight(0.01);
                    $sketch->setDimension(implode(', ',$sizes));
                    $sketch->setNote(implode(', ',$sizes).','.$stampQuote->getAdditionalComment());
                    $sketch->setNote($custom->getComment());
                    $sketch->setExtension($custom->getExtension());
                    $sketch->setOriginalFile($custom->getOriginalFile());
                    $sketch->setStampShape($custom->getStampShape());
                    $sketch->setFile($custom->getFile());
                    $sketch->setSize($custom->getFileSize());
                    $manager->persist($sketch);
                }
            }

            $stampQuote->setStatus($stampQuote::STATUS_QUOTE_STARTED);
            $manager->persist($stampQuote);
            $manager->flush();

            $this->addFlash('success', 'Quote has been created.');
            return $this->redirectToRoute('quote_show', ['id' => $quote->getId()]);
        }else{
            $this->addFlash('danger', 'This quote started.');
            return $this->redirectToRoute('admin_custom_order_show', ['id' => $stampQuote->getId()]);
        }
    }


    /**
     * @Route("/admin/custom-order/{id}/reminder", name="admin_custom_order_reminder")
     * @IsGranted("ROLE_ADMIN")
     */
    public function sendReminderEmail(StampQuote $stampQuote, EntityManagerInterface $manager, Mailer $mailer)
    {
//        if($stampQuote->getStatus() == $stampQuote::STATUS_CREATED){

            $mailer->sendReminderEmail($stampQuote, $this->getParameter('my_personal_email'));

            $stampQuote->setStatus($stampQuote::STATUS_REMINDER_ISSUED);
            $stampQuote->setUpdatedAt(new \DateTime('now'));

            $manager->persist($stampQuote);
            $manager->flush();
            $this->addFlash('success','Reminder email has been issued to <b>'.$stampQuote->getEmail().'</b>.');
            return $this->redirectToRoute('admin_custom_orders');
//        }

//        $this->addFlash('danger', 'There was an error to send reminder');
//        return $this->redirectToRoute('admin_custom_order_show', ['id' => $stampQuote->getId()]);
    }


    /**
     * @Route("/admin/custom-order/{id}/close", name="admin_custom_order_close_quote")
     * @IsGranted("ROLE_ADMIN")
     */
    public function closeCustomQuote(StampQuote $stampQuote, EntityManagerInterface $manager)
    {
//        if($stampQuote->getStatus() == $stampQuote::STATUS_CREATED){
            $stampQuote->setStatus($stampQuote::STATUS_CLOSED);
            $stampQuote->setUpdatedAt(new \DateTime('now'));
            $manager->flush();
            $this->addFlash('success', sprintf('Status of Customer Quote has been changed to %s', $stampQuote::STATUS_CLOSED));
            return $this->redirectToRoute('admin_custom_orders');
//        }

//        $this->addFlash('danger', sprintf('Could not close because status is %s', $stampQuote->getStatus()));
//        return $this->redirectToRoute('admin_custom_order_show', ['id' => $stampQuote->getId()]);
    }
}
