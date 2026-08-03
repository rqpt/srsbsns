<?php

namespace App\Controller;

use App\Entity\Contact;
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
    ): Response {
        $token = $request->headers->get('X-CSRF-TOKEN');

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_contact', $token))) {
            return new Response('Invalid CSRF token', Response::HTTP_FORBIDDEN);
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        return new Response(null, Response::HTTP_OK);
    }
}
