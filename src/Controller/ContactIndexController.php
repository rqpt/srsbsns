<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Event\ContactCreatedEvent;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route(
    path: '/',
    name: 'app_contact_index',
    methods: ['GET', 'POST'],
)]
final class ContactIndexController extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        Recaptcha3Validator $recaptcha3Validator,
        #[Autowire('%karser_recaptcha3.score_threshold%')] float $recaptchaThreshold,
    ): Response {
        $contact = new Contact;

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $eventDispatcher->dispatch(new ContactCreatedEvent($contact));

            $recaptchaScore = $recaptcha3Validator
                ->getLastResponse()
                ?->getScore();

            $this->addFlash('success', sprintf(
                <<<'FLASH'
                Contact created successfully!
                <br>
                (reCAPTCHA threshold: %.2f, reCAPTCHA score: %.2f)
                FLASH,
                $recaptchaThreshold,
                $recaptchaScore ?? 0.0
            ));

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
}
