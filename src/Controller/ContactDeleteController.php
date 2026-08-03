<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route(
    path: '/{id}',
    name: 'app_contact_delete',
    methods: ['DELETE'],
)]
final class ContactDeleteController extends AbstractController
{
    public function __invoke(
        Contact $contact,
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        ContactRepository $contactRepository,
    ): Response {
        $csrfToken = $request->headers
            ->get('X-CSRF-TOKEN');

        $csrfTokenIsValid = $csrfTokenManager->isTokenValid(
            new CsrfToken('delete_contact', $csrfToken),
        );

        if (!$csrfTokenIsValid) {
            return new Response('Invalid CSRF token', Response::HTTP_FORBIDDEN);
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        if ($contactRepository->count() === 0) {
            return $this->render('contact/_empty_list.html.twig', [
                'hx_target' => '#contacts-container',
            ]);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
