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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendContactUs($contactFormData, $email)
    {
        $email = (new TemplatedEmail())
            ->from('info@cocktailbrandalism.com')
            ->to($email)
            ->subject('CocktailBrandalism.com - New Contact us')
            ->htmlTemplate('emails/_contact_us.html.twig')
            ->context([
                'data' => $contactFormData
            ]);

        $this->mailer->send($email);
    }

    public function sendEnquiry(StampQuote $enquiry, $email)
    {
        $email = (new TemplatedEmail())
            ->from('info@cocktailbrandalism.com')
            ->to($email)
            ->subject('CocktailBrandalism.com - New Stamp Order')
            ->htmlTemplate('emails/_enquiry.html.twig')
            ->context([
                'enquiry' => $enquiry
            ]);

        $this->mailer->send($email);
    }

    public function sendReminderEmail(StampQuote $stampQuote)
    {
        $email = (new TemplatedEmail())
            ->from('info@cocktailbrandalism.com')
            ->to($stampQuote->getEmail())
            ->subject('CocktailBrandalism.com - Reminder Of Your Custom Order')
            ->htmlTemplate('emails/_reminder.html.twig')
            ->context([
                'stampQuote' => $stampQuote
            ]);

        $this->mailer->send($email);
    }

}