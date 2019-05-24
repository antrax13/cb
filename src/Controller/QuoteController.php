<?php

namespace App\Controller;

use App\Entity\BrandSketch;
use App\Entity\Invoice;
use App\Entity\Quote;
use App\Entity\SketchReference;
use App\Form\BrandSketchFormType;
use App\Form\InvoiceNewFormType;
use App\Form\QuoteFormType;
use App\Form\SketchEditType;
use App\Repository\InvoiceRepository;
use App\Repository\QuoteRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class QuoteController extends AbstractController
{
    /**
     * @Route("/quotes", name="quotes")
     */
    public function index(QuoteRepository $repository)
    {
        $breadcrumbs = ['Quotes'];
        $array = [];
        $quotes = $repository->findBy([
            'isRemoved' => false
        ], [
            'id' => 'DESC'
        ]);
        foreach($quotes as $quote){
            $array[] = [
                'id' => $quote->getId(),
                'quote_id' =>  '#CBQ00'.$quote->getId(),
                'quote_shipping_country' => $quote->getShippingCountry() ? $quote->getShippingCountry()->getName() : 'Not Known',
                'request' => $quote->getRequest(),
                'customer' => $quote->getCustomer()->getEmail(),
                'sketches' => count($quote->getBrandSketchesNotRemoved()),
                'generated_at' => $quote->getGeneratedAt() ? $quote->getGeneratedAt()->format('Y-m-d') : null,
                'status' => $quote->getStatus(),
            ];
        }

        return $this->render('quote/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'quotes' => $array,
        ]);
    }

    /**
     * @Route("/quote/new", name="quote_new")
     */
    public function newAction(Request $request, ObjectManager $manager)
    {
        $breadcrumbs = ['Quote', 'New'];

        $form = $this->createForm(QuoteFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $quote = $form->getData();

            $manager->persist($quote);
            $manager->flush();

            $this->addFlash('success', 'Quote has been created');
            return $this->redirectToRoute('quote_sketch_show', [
                'id' => $quote->getId()
            ]);
        }

        return $this->render('quote/new.html.twig',[
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/quote/{id}", name="quote_show")
     */
    public function showAction(Quote $quote){
        if($quote->getIsRemoved()){
            $this->addFlash('danger','Quote has been removed. Contact Administrator to update Quote: '.$quote->getId().' manually.');
             return $this->redirectToRoute('quotes');
        }

        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId()];

        return $this->render('quote/show.html.twig', [
            'title' => 'Quote',
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
        ]);
    }

    /**
     * @Route("/quote/{id}/edit", name="quote_show_edit")
     */
    public function editQuoteAction(Request $request,Quote $quote, ObjectManager $manager){
        if($quote->getIsRemoved()){
            $this->addFlash('danger','Quote has been removed. Contact Administrator to update Quote: '.$quote->getId().' manually.');
            return $this->redirectToRoute('quotes');
        }

        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId(), 'Edit'];

        $form = $this->createForm(QuoteFormType::class, $quote);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $quote = $form->getData();

            $manager->persist($quote);
            $manager->flush();

            $this->addFlash('success', 'Quote has been updated');

            return $this->redirectToRoute('quote_show', ['id' => $quote->getId()]);
        }

        return $this->render('quote/edit_details.html.twig', [
            'title' => 'Quote',
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/quote/{id}/sketch", name="quote_sketch_show")
     */
    public function showQuoteSketches(Request $request, Quote $quote, ObjectManager $manager, UploaderHelper $uploaderHelper)
    {
        if($quote->getIsRemoved()){
            $this->addFlash('danger','Quote has been removed. Contact Administrator to update Quote: '.$quote->getId().' manually.');
            return $this->redirectToRoute('quotes');
        }
        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId(), 'Sketch'];

        $sketch = new BrandSketch();
        $form = $this->createForm(BrandSketchFormType::class, $sketch);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $fileExtension = $file->guessExtension();
            $fileName = md5(uniqid()) . '.' . $fileExtension;
            $fileOriginal = $file->getClientOriginalName();
            $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';

            $fileName = $uploaderHelper->uploadFile($file, $this->getParameter('sketch_dir'));



            $sketch->setQuote($quote);
            $sketch->setOriginalFile($fileOriginal);
            $sketch->setSize($fileSize);
            $sketch->setExtension($fileExtension);
            $sketch->setFile($fileName);
            $sketch->setPrice($sketch->getPrice());
            $sketch->setWeight($sketch->getWeight());
            $sketch->setDimension($sketch->getDimension());
            $sketch->setStampType($sketch->getStampType());

            $manager->persist($sketch);
            $manager->flush();

            $this->addFlash('success', 'Sketch has been uploaded. Thank you.');

            return $this->redirectToRoute('quote_sketch_show', [
                'id' => $quote->getId(),
            ]);
        }

        return $this->render('quote/sketch.html.twig', [
            'title' => $breadcrumbs[2],
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sketch/{id}/delete", name="delete_sketch")
     */
    public function deleteSketch(BrandSketch $sketch, ObjectManager $manager)
    {
        $sketch->setIsRemoved(true);
        $manager->persist($sketch);
        $manager->flush();

        $this->addFlash('success', 'Sketch has been removed. Thank you.');

        return $this->redirectToRoute('quote_sketch_show', [
            'id' => $sketch->getQuote()->getId(),
        ]);
    }

    /**
     * @Route("/sketch/{id}/undo-delete", name="undo_delete_sketch")
     */
    public function undoDeleteSketch(BrandSketch $sketch, ObjectManager $manager)
    {
        $sketch->setIsRemoved(false);
        $manager->persist($sketch);
        $manager->flush();

        $this->addFlash('success', 'Sketch has been reverted back to quote.');

        return $this->redirectToRoute('quote_sketch_show', [
            'id' => $sketch->getQuote()->getId(),
        ]);
    }

    /**
     * @Route("/quote/{id}/delete", name="delete_quote")
     */
    public function deleteQuote(Quote $quote, ObjectManager $manager)
    {
        $quote->setIsRemoved(true);
        $manager->persist($quote);
        $manager->flush();

        $this->addFlash('success', 'Quote has been removed. Thank you.');

        return $this->redirectToRoute('quotes');
    }

    /**
     * @Route("/sketch/{id}/edit", name="edit_sketch")
     */
    public function editSketch(Request $request, BrandSketch $sketch, ObjectManager $manager, UploaderHelper $uploaderHelper)
    {

        $breadcrumbs = ['Sketch', $sketch->getId(), 'Edit'];

        $form = $this->createForm(SketchEditType::class, $sketch);
        $form->handleRequest($request);

        $oldSketch = clone $sketch;

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $file */
            $file = $form->get('newFile')->getData();
            if($file){
                $fileExtension = $file->guessExtension();
                $fileOriginal = $file->getClientOriginalName();
                $fileSize = number_format($file->getSize() / 1048576, 2) . 'MB';
                $fileName = $uploaderHelper->uploadFile($file, $this->getParameter('sketch_dir'));

                $sketch->setOriginalFile($fileOriginal);
                $sketch->setSize($fileSize);
                $sketch->setExtension($fileExtension);
                $sketch->setFile($fileName);

                $reference = new SketchReference($sketch, $oldSketch->getFile(), $oldSketch->getExtension(), $oldSketch->getOriginalFile(), $oldSketch->getSize());
                $manager->persist($reference);
            }

            $manager->persist($sketch);
            $manager->flush();

            $this->addFlash('success', 'Sketch has been updated.');
            return $this->redirectToRoute('quote_sketch_show', [
                'id' => $sketch->getQuote()->getId()
            ]);
        }

        return $this->render('quote/sketch_edit.html.twig', [
            'sketch' => $sketch,
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Sketch Edit',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/quote/{id}/invoice", name="quote_invoice")
     */
    public function createInvoice(Request $request, Quote $quote, ObjectManager $manager, InvoiceRepository $repository)
    {
        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId(), 'Invoice'];

        $invoices = $repository->findAll();

        $invoice = new Invoice();
        $invoice->setQuote($quote);
        $invoice->setGeneratedAt(new \DateTime());
        $invoice->setStatus('CREATED');

        $form = $this->createForm(InvoiceNewFormType::class,$invoice);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $invoice = $form->getData();
            $reference = str_pad(count($invoices)+1,'5','0', STR_PAD_LEFT).'N/'.date('Y');
            $invoice->setReference($reference);
            $manager->persist($invoice);
            $manager->flush();

            $this->addFlash('success','Invoice has been created');
            return $this->redirectToRoute('invoices_show', ['id' => $invoice->getId()]);
        }

        return $this->render('quote/invoice.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Invoice',
            'quote' => $quote,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/quote/{id}/reorder", name="quote_sketch_reorder", methods="POST")
     */
    public function reorderSketchesInQuote(Quote $quote, Request $request, EntityManagerInterface $em)
    {
        $orderedIds = json_decode($request->getContent(), true);
        if($orderedIds === false){
            return $this->json(['detail' => 'Invalid body'], 400);
        }

        // from (position) => (id) to (id) => (position)
        $orderedIds = array_flip($orderedIds);
        foreach($quote->getBrandSketches() as $sketch){
            $sketch->setPosition($orderedIds[$sketch->getId()]);
        }
        $em->flush();

        return $this->json($quote->getBrandSketches(), 200, [], ['groups' => ['main']]);
    }
}
