<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/',
    name: 'app_contact_index',
    methods: ['GET', 'POST'],
)]
final class ContactController extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        string $adminEmail,
    ): Response {
        $contact = new Contact;

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->sendNotificationMails($contact, $mailer, $adminEmail);

            $this->addFlash('success', 'Contact created successfully!');

            return $this->redirectToRoute('app_contact_index');
        }

        $contacts = $entityManager
            ->getRepository(Contact::class)
            ->findAll();

        return $this->render(
            'contact/index.html.twig',
            compact('form', 'contacts'),
        );
    }

    private function sendNotificationMails(
        Contact $contact,
        MailerInterface $mailer,
        string $adminEmail,
    ): void {
        $emailToContact = (new TemplatedEmail)
            ->to($contact->getEmail())
            ->subject('Contact saved')
            ->htmlTemplate('emails/contact_confirmation.html.twig')
            ->context(['contact' => $contact]);

        $emailToAdmin = (new TemplatedEmail)
            ->to($adminEmail)
            ->subject('New Contact Submission')
            ->htmlTemplate('emails/contact_admin_notification.html.twig')
            ->context(['contact' => $contact]);

        $mailer->send($emailToContact);
        $mailer->send($emailToAdmin);
    }
}
