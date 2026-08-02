<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/api/contacts/{id}',
    name: 'api_contact_delete',
    methods: ['DELETE'],
    format: 'json',
)]
final class ContactDeleteController extends AbstractController
{
    public function __invoke(
        Contact $contact,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $entityManager->remove($contact);
        $entityManager->flush();

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
        );
    }
}
