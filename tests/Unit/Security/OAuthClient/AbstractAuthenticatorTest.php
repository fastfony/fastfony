<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\OAuthClient;

use App\Entity\User\User;
use App\Factory\UserFactory;
use App\Security\OAuthClient\AbstractAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class AbstractAuthenticatorTest extends TestCase
{
    private AbstractAuthenticator|MockObject $authenticator;
    private ClientRegistry|MockObject $clientRegistry;
    private UserFactory|MockObject $userFactory;
    private OAuth2Client|MockObject $client;
    private AccessToken|MockObject $accessToken;

    protected function setUp(): void
    {
        $this->clientRegistry = $this->createMock(ClientRegistry::class);
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->client = $this->createMock(OAuth2Client::class);
        $this->accessToken = $this->createMock(AccessToken::class);

        $this->authenticator = $this->getMockForAbstractClass(
            AbstractAuthenticator::class,
            [$this->clientRegistry, $this->userFactory]
        );
    }

    public function testSupports(): void
    {
        $this->authenticator->method('getClientName')->willReturn('test');

        $request = new Request([], [], ['_route' => 'connect_test_check']);
        $this->assertTrue($this->authenticator->supports($request));

        $request = new Request([], [], ['_route' => 'some_other_route']);
        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testAuthenticate(): void
    {
        $this->authenticator->method('getClientName')->willReturn('github');
        $this->clientRegistry->method('getClient')->with('github')->willReturn($this->client);

        $this->client->method('fetchUserFromToken')->with($this->accessToken)->willReturn(new \stdClass());
        $this->userFactory->method('fromGithub')->willReturn(new User());

        $this->clientRegistry->method('getClient')
            ->willReturn($this->client);

        $this->client->method('getAccessToken')
            ->willReturn($this->accessToken);

        $this->accessToken->method('getToken')
            ->willReturn('example_id_user');

        $request = new Request();
        $passport = $this->authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $passport);
        $this->assertInstanceOf(UserBadge::class, $passport->getBadge(UserBadge::class));
        $this->assertEquals('example_id_user', $passport->getBadge(UserBadge::class)->getUserIdentifier());
    }

    public function testOnAuthenticationSuccess(): void
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $token = $this->createMock(TokenInterface::class);
        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function testOnAuthenticationFailure(): void
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $exception = new AuthenticationException('Authentication failed');
        $response = $this->authenticator->onAuthenticationFailure($request, $exception);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/login', $response->getTargetUrl());
    }
}
