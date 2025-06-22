<?php

declare(strict_types=1);

namespace App\Security\OAuthClient;

class GoogleAuthenticator extends AbstractAuthenticator
{
    public function getClientName(): string
    {
        return 'google';
    }
}
