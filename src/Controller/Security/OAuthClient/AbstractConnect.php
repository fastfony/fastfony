<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthClient;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractConnect extends AbstractController
{
    /** @var array<string> */
    protected array $scopes = [];

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
    ) {
    }

    public function __invoke(string $service): RedirectResponse
    {
        return $this->clientRegistry->getClient($service)
            ->redirect($this->scopes, []);
    }
}
