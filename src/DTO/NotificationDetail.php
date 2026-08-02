<?php

namespace App\DTO;

readonly class NotificationDetail
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public string $subject,
        public string $template,
        public array $context = [],
    ) {}
}
