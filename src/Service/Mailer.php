<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 26/02/2019
 * Time: 08:59
 */

namespace App\Service;


use App\Entity\StampQuote;
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

    public function sendContactUs($contactFormData, $email)
    {
        $body = $this->twig->render('emails/_contact_us.html.twig', [
            'data' => $contactFormData
        ]);

        $message = (new \Swift_Message('CocktailBrandalism.com - New Contact us'))
            ->setFrom('info@cocktailbrandalism.com', 'Cocktail Brandalism')
            ->setTo($email)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

    public function sendEnquiry(StampQuote $enquiry, $email)
    {
        $body = $this->twig->render('emails/_enquiry.html.twig', [
            'enquiry' => $enquiry
        ]);

        $message = (new \Swift_Message('CocktailBrandalism.com - New Stamp Order'))
            ->setFrom('info@cocktailbrandalism.com', 'Cocktail Brandalism')
            ->setTo($email)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

    public function sendReminderEmail(StampQuote $stampQuote)
    {
        $body = $this->twig->render('emails/_reminder.html.twig', [
            'stampQuote' => $stampQuote
        ]);

        $message = (new \Swift_Message('CocktailBrandalism.com - Reminder Of Your Custom Order'))
            ->setFrom('info@cocktailbrandalism.com', 'Cocktail Brandalism')
            ->setTo($stampQuote->getEmail())
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }

}