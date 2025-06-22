<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthServer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Jwks extends AbstractController
{
    public function __construct(
        private readonly string $oauthServerPublicKeyPath,
    ) {
    }

    #[Route('.well-known/jwks.json', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        // Load the public key from the filesystem and use OpenSSL to parse it.
        $publicKey = openssl_pkey_get_public(file_get_contents($this->oauthServerPublicKeyPath));
        $details = openssl_pkey_get_details($publicKey);
        $jwks = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'alg' => 'RS256',
                    'use' => 'sig',
                    'kid' => '1',
                    'n' => strtr(rtrim(base64_encode($details['rsa']['n']), '='), '+/', '-_'),
                    'e' => strtr(rtrim(base64_encode($details['rsa']['e']), '='), '+/', '-_'),
                ],
            ],
        ];

        return $this->json($jwks);
    }
}
