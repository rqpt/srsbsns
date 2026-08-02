<?php

namespace App\Event;

use App\Entity\Contact;
use Symfony\Contracts\EventDispatcher\Event;

final class ContactCreatedEvent extends Event
{
    public function __construct(
        private readonly Contact $contact,
    ) {}

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
