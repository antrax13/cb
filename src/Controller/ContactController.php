<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, Mailer $mailer)
    {
        $breadcrumbs = [
            [
                'name' => 'Contact',
                'url' => $this->generateUrl('contact')
            ]
        ];
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $this->captchaverify($request->get('g-recaptcha-response'))){
            $this->addFlash('success','Thank you. Message has been sent. We will answer your enquire as soon as possible.');
            $contactFormData = $form->getData();

            $mailer->sendContactUs($contactFormData, $this->getParameter('my_personal_email'));

            return $this->redirectToRoute('contact');
        }

        if($form->isSubmitted() && $form->isValid() && !$this->captchaverify($request->get('g-recaptcha-response'))){
            $this->addFlash('danger', 'reCaptcha required.');
        }

        return $this->render('contact/index.html.twig', [
            'title' => $breadcrumbs[0]['name'],
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }

    function captchaverify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "secret"=>"6LfL8ZMUAAAAAIPducfyq_3lPgk0BaB7QCsVxiU0",
            "response"=>$recaptcha
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }
}
