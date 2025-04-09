<?php

declare(strict_types=1);

namespace App\Pro\Controller\Security\OAuthClient;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractCheck extends AbstractController
{
    public function __invoke(): void
    {
        // this method leaves blank intentionally, an Authenticator will handle the request
    }
}
