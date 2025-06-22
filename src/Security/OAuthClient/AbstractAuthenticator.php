<?php

declare(strict_types=1);

namespace App\Security\OAuthClient;

use App\Factory\UserFactory;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

abstract class AbstractAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public function __construct(
        protected readonly ClientRegistry $clientRegistry,
        protected readonly UserFactory $userFactory,
    ) {
    }

    abstract public function getClientName(): string;

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return 'connect_'.$this->getClientName().'_check' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient($this->getClientName());
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge(
                $accessToken->getToken(),
                function () use ($accessToken, $client) {
                    $fetchUser = $client->fetchUserFromToken($accessToken);
                    $methodName = 'from'.ucfirst($this->getClientName());

                    return $this->userFactory->$methodName($fetchUser);
                }
            )
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName,
    ): ?Response {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception,
    ): ?Response {
        /* @phpstan-ignore method.notFound */
        $request->getSession()->getFlashBag()->add(
            'error',
            'Authentication failed ('.$exception->getMessage().')',
        );

        return $this->start($request, $exception);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(
        Request $request,
        ?AuthenticationException $authException = null,
    ): Response {
        return new RedirectResponse(
            '/login',
            Response::HTTP_TEMPORARY_REDIRECT,
        );
    }
}
