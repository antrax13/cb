<?php

namespace App\Controller;

use App\Entity\BrandSketch;
use App\Entity\Quote;
use App\Form\BrandSketchFormType;
use App\Form\QuoteFormType;
use App\Repository\QuoteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        ]);
        foreach($quotes as $quote){
            $array[] = [
                'id' => $quote->getId(),
                'quote_id' =>  '#CBQ00'.$quote->getId(),
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
    public function showAction(Request $request,Quote $quote, ObjectManager $manager){
        if($quote->getIsRemoved()){
            $this->addFlash('danger','Quote has been removed. Contact Administrator to update Quote: '.$quote->getId().' manually.');
             return $this->redirectToRoute('quotes');
        }

        $breadcrumbs = ['Quote', '#CBQ00'.$quote->getId()];

        $form = $this->createForm(QuoteFormType::class, $quote);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $quote = $form->getData();

            $manager->persist($quote);
            $manager->flush();

            $this->addFlash('success', 'Quote has been updated');

            return $this->redirectToRoute('quotes');
        }

        return $this->render('quote/show.html.twig', [
            'title' => 'Quote',
            'breadcrumbs' => $breadcrumbs,
            'quote' => $quote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quote/{id}/sketch", name="quote_sketch_show")
     */
    public function showQuoteSketches(Request $request, Quote $quote, ObjectManager $manager)
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

            // moves the file to the directory where tags are stored
            $file->move(
                $this->getParameter('sketch_dir'),
                $fileName
            );

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
}
