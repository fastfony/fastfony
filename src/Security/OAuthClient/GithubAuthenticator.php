<?php

declare(strict_types=1);

namespace App\Security\OAuthClient;

class GithubAuthenticator extends AbstractAuthenticator
{
    public function getClientName(): string
    {
        return 'github';
    }
}
