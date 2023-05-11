<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService {
    private MailerInterface $mailer ;
    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer ;
}
    public function sendMail(string $from,
                             string $to,
                             string $subject,
                             string $template,
                             array $contexts):void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($contexts);

        $this->mailer->send($email);
    }
}
