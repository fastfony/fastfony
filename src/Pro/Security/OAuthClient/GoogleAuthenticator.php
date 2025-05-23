<?php

declare(strict_types=1);

namespace App\Pro\Security\OAuthClient;

class GoogleAuthenticator extends AbstractAuthenticator
{
    public function getClientName(): string
    {
        return 'google';
    }
}
