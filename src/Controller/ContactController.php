<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        $breadcrumbs = ['Contact'];
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success','Thank you. Message has been sent. We will answer your enquire as soon as posible.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'title' => $breadcrumbs[0],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }
}
