<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 26/02/2019
 * Time: 08:59
 */

namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendContactUs($contactFormData)
    {
        $body = $this->twig->render('emails/_contact_us.html.twig', [
            'data' => $contactFormData
        ]);

        $message = (new \Swift_Message('CocktailBrandalism.com - Thank you for contacting us'))
            ->setFrom('info@peterkosak.com')
            ->setTo('pkosak@pim-ltd.com')
            ->setBody($body, 'text/html');
        $this->mailer->send($message);

        $message->setTo('info@cocktailbrandalism.com');
        $this->mailer->send($message);
    }

}