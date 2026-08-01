<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/',
    name: 'app_contact_new',
    methods: ['GET', 'POST'],
)]
final class ContactController extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $contact = new Contact;

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact created successfully!');

            return $this->redirectToRoute('app_contact_new');
        }

        return $this->render('contact/new.html.twig', compact('form'));
    }
}
