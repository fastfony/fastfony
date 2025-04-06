<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    {
        // If the request is on /authorize Oauth path, redirect to the login page
        if ('/authorize' === $request->getPathInfo()) {
            return new RedirectResponse(
                $this->router->generate(
                    'login',
                    [
                        '_target_path' => $request->query->get('redirect_uri'),
                    ],
                )
            );
        }

        // add a custom flash message and redirect to the login page
        /* @phpstan-ignore method.notFound */
        $request->getSession()->getFlashBag()->add('note', 'flash.require_login');

        return new RedirectResponse($this->router->generate('request_login_link'));
    }
}
