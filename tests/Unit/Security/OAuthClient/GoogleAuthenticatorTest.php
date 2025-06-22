<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\OAuthClient;

use App\Factory\UserFactory;
use App\Security\OAuthClient\GoogleAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GoogleAuthenticatorTest extends TestCase
{
    private GoogleAuthenticator $authenticator;
    private ClientRegistry|MockObject $clientRegistry;
    private UserFactory|MockObject $userFactory;

    protected function setUp(): void
    {
        $this->clientRegistry = $this->createMock(ClientRegistry::class);
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->authenticator = new GoogleAuthenticator($this->clientRegistry, $this->userFactory);
    }

    public function testGetClientName(): void
    {
        $this->assertEquals('google', $this->authenticator->getClientName());
    }

    public function testSupports(): void
    {
        $request = new Request([], [], ['_route' => 'connect_google_check']);
        $this->assertTrue($this->authenticator->supports($request));

        $request = new Request([], [], ['_route' => 'some_other_route']);
        $this->assertFalse($this->authenticator->supports($request));
    }
}
