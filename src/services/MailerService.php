<?php

namespace App\services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {

    }
    public function sendEmail(
        string $subject='',
        string $from='mohamed.test.noreplay@gmail.com',
        string $to='mohamedhabibhajjej@gmail.com',
        string $message='',
    ): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
           // ->text('Sending emails is fun again!')
            ->html($message);

        $this->mailer->send($email);

        // ...
    }

}