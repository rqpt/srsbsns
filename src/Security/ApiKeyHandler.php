<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiKeyHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        #[Autowire('%env(API_KEY)%')]
        #[\SensitiveParameter] private string $apiSecret,
    ) {}

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (!hash_equals($this->apiSecret, $accessToken)) {
            throw new BadCredentialsException('Invalid API Key.');
        }

        return new UserBadge('api_service_user');
    }
}
