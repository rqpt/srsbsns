<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/{id}',
    name: 'app_contact_delete',
    methods: ['DELETE'],
)]
final class ContactDeleteController extends AbstractController
{
    public function __invoke(
        Contact $contact,
        EntityManagerInterface $entityManager,
    ): Response {
        $entityManager->remove($contact);
        $entityManager->flush();

        return new Response(null, Response::HTTP_OK);
    }
}
