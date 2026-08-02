<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Event\ContactCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(
    path: '/api/contacts',
    name: 'api_contact_create',
    methods: ['POST'],
    format: 'json',
)]
final class ContactCreateController extends AbstractController
{
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
    ): JsonResponse {
        try {
            $contact = $serializer->deserialize(
                data: $request->getContent(),
                type: Contact::class,
                format: 'json',
            );
        } catch (\Exception) {
            return $this->json(
                ['error' => 'Invalid JSON format.'],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $errors = $validator->validate($contact);

        if (count($errors) > 0) {
            return $this->json(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $entityManager->persist($contact);
        $entityManager->flush();

        $eventDispatcher->dispatch(new ContactCreatedEvent($contact));

        return $this->json($contact, Response::HTTP_CREATED);
    }
}
