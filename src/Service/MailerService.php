<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class MailerService
{
    public function __construct(private MailerInterface $mailer){}
    public function sendEmail($to = 'ghassennaouar7@example.com'): void
    {
        $email = (new Email())
            ->from('symfonyecommerce34@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>helliw !!</p>');

        $this->mailer->send($email);
    }

}