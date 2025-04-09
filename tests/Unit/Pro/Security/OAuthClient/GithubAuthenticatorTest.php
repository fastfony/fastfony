<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Security\OAuthClient;

use App\Factory\UserFactory;
use App\Pro\Security\OAuthClient\GithubAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GithubAuthenticatorTest extends TestCase
{
    private GithubAuthenticator $authenticator;
    private ClientRegistry|MockObject $clientRegistry;
    private UserFactory|MockObject $userFactory;

    protected function setUp(): void
    {
        $this->clientRegistry = $this->createMock(ClientRegistry::class);
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->authenticator = new GithubAuthenticator($this->clientRegistry, $this->userFactory);
    }

    public function testGetClientName(): void
    {
        $this->assertEquals('github', $this->authenticator->getClientName());
    }

    public function testSupports(): void
    {
        $request = new Request([], [], ['_route' => 'connect_github_check']);
        $this->assertTrue($this->authenticator->supports($request));

        $request = new Request([], [], ['_route' => 'some_other_route']);
        $this->assertFalse($this->authenticator->supports($request));
    }
}
