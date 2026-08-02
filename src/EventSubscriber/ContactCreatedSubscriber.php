<?php

namespace App\EventSubscriber;

use App\DTO\NotificationDetail;
use App\Event\ContactCreatedEvent;
use App\Service\NotificationService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContactCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly NotificationService $notificationService,
        #[Autowire('%app.admin_email%')] private readonly string $adminEmail,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreatedEvent::class => 'onContactCreated',
        ];
    }

    public function onContactCreated(ContactCreatedEvent $event): void
    {
        $contact = $event->getContact();

        $recipients = [
            $this->adminEmail => new NotificationDetail(
                subject: 'New Contact Saved',
                template: 'emails/contact_admin_notification.html.twig',
                context: ['contact' => $contact],
            ),
            $contact->getEmail() => new NotificationDetail(
                subject: 'New Contact Saved',
                template: 'emails/contact_confirmation.html.twig',
                context: ['contact' => $contact],
            ),
        ];

        $this->notificationService->sendNotificationMails($recipients);
    }
}
