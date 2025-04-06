# OAuth client

You can add an OAuth provider to your project by following these steps.

## 1. Find the client library for your needs and required it

List of available libraries:
https://github.com/knpuniversity/oauth2-client-bundle?tab=readme-ov-file#step-1-download-the-client-library

For example:

```bash
composer require league/oauth2-google
```

## 2. Create the specific OAuth controllers

Create the `src/Controller/Security/OAuthClient/Provider/Check.php` and
`src/Controller/Security/OAuthClient/Provider/Connect.php` files, respectively extends
`src/Controller/Security/OAuthClient/AbstractCheck.php` and `src/Controller/Security/OAuthClient/AbstractConnect.php`.

(replace `Provider` by the name of your OAuth provider)

With this code:

```php
<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthClient\Provider;

use App\Pro\Controller\Security\OAuthClient\AbstractCheck;use Symfony\Component\Routing\Attribute\Route;

#[Route('/connect/provider/check', name: 'connect_provider_check')]
class Check extends AbstractCheck
{
}
```

```php
<?php

declare(strict_types=1);

namespace App\Controller\Security\OAuthClient\Provider;

use App\Pro\Controller\Security\OAuthClient\AbstractConnect;use Symfony\Component\HttpFoundation\RedirectResponse;use Symfony\Component\Routing\Attribute\Route;

#[Route('/connect/provider', name: 'connect_provider', methods: ['GET'])]
class Connect extends AbstractConnect
{
    public function __invoke(string $service = 'provider'): RedirectResponse
    {
        $this->scopes = []; // Customize the scopes

        return parent::__invoke($service);
    }
}
```

## 3. Create the specific OAuth authenticator

Create the `src/Security/OAuthClient/ProviderAuthenticator.php` file and add the code below and adapt it to your needs.

```php
<?php

declare(strict_types=1);

namespace App\Security\OAuthClient;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
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

class ProviderAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return 'connect_provider_check' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('provider');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge(
                $accessToken->getToken(),
                function () use ($accessToken, $client) {
                    $fetchUser = $client->fetchUserFromToken($accessToken);

                    $existingUser = $this->userRepository->findOneBy(['email' => $fetchUser->getEmail()]);
                    if ($existingUser) {
                        return $existingUser;
                    }

                    $user = (new User())
                        ->setEmail($fetchUser->getEmail());

                    $this->userRepository->save($user);

                    return $user;
                }
            )
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName,
    ): ?Response {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception,
    ): ?Response {
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
```

## 4. Edit your security.yaml configuration:

```yaml
security:
    firewalls:
        #...

        main:
            #...
            custom_authenticators: ...
                - App\Security\OAuthClient\ProviderAuthenticator
```

## 5. Add a link to the login provider

```twig
<a href="{{ path('connect_provider') }}">Connect with Provider</a>
```

## 6. Configure the provider in config/packages/knpu_oauth2_client.yaml

```yaml
knpu_oauth2_client:
    clients:
        provider:
            type: provider
            client_id: '%env(database:PROVIDER_CLIENT_ID)%'
            client_secret: '%env(database:PROVIDER_CLIENT_SECRET)%'
            redirect_route: connect_provider_check
```

## 7. Create your OAuth app on the provider

## 8. Create two parameters in database via the interface:

Go to : https://fastfony-pro.wip/admin/parameter-crud
and create two parameters with the following names:

- PROVIDER_CLIENT_ID
- PROVIDER_CLIENT_SECRET

(replace `PROVIDER` by the name of your OAuth provider)

and set the values of your OAuth provider in this interface:
https://fastfony-pro.wip/admin?routeName=admin_parameters

## 9. Test!
