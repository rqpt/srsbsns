<?php

namespace App\Tests\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactFormCreatesContactAndRedirects(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save')
            ->form([
                'contact[name]' => 'Donald',
                'contact[surname]' => 'Trump',
                'contact[phoneNumber]' => '0123456789',
                'contact[email]' => 'donald.trump@example.com',
            ]);

        $client->submit($form);

        $this->assertResponseRedirects('/');
        $client->followRedirect();

        $this->assertSelectorTextContains(
            selector: '.alert-success',
            text: 'Contact created successfully!',
        );

        $entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $savedContact = $entityManager->getRepository(Contact::class)
            ->findOneBy(['email' => 'donald.trump@example.com']);

        $this->assertNotNull($savedContact);
        $this->assertSame('Donald', $savedContact->getName());
    }
}
