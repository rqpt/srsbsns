<?php

namespace App\Service;

use App\DTO\NotificationDetail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class NotificationService
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {}

    /**
     * @param array<string, NotificationDetail> $recipients Keyed by target email address
     */
    public function sendNotificationMails(array $recipients): void
    {
        foreach ($recipients as $address => $details) {
            $email = (new TemplatedEmail())
                ->to(Address::create($address))
                ->subject($details->subject)
                ->htmlTemplate($details->template)
                ->context($details->context);

            $this->mailer->send($email);
        }
    }
}
