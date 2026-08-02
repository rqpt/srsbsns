<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $contact = new Contact;

        $contact->setName('Pieter Ernst');
        $contact->setSurname('Vermeulen');
        $contact->setPhoneNumber('0674417057');
        $contact->setEmail('ernstvermeulen@proton.me');

        $manager->persist($contact);
        $manager->flush();
    }
}
