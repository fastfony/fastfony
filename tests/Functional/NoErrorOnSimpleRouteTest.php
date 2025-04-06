<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class NoErrorOnSimpleRouteTest extends WebTestCase
{
    public function testAllRoutes(): void
    {
        $client = static::createClient();
        $router = static::getContainer()->get(RouterInterface::class);
        $routeCollection = $router->getRouteCollection();

        // Check all routes without user connection
        $this->getAllRoutes($routeCollection, $client);

        // Check all routes with user connection
        $superAdmin = static::getContainer()->get(UserRepository::class)
            ->findOneByEmail('superadmin@fastfony.com');
        $client->loginUser($superAdmin);
        $this->getAllRoutes($routeCollection, $client);
    }

    private function getAllRoutes(RouteCollection $routeCollection, KernelBrowser $client): void
    {
        foreach ($routeCollection as $routeName => $route) {
            if ($this->isSimpleRoute($routeName, $route)) {
                $client->request('GET', $route->getPath());
                $response = $client->getResponse();
                $this->assertTrue(
                    $response->isSuccessful() || $response->isRedirection(),
                    \sprintf('The route "%s" did not return a successful response.', $routeName)
                );
            }
        }
    }

    private function isSimpleRoute(string $routeName, Route $route): bool
    {
        if (\count($route->getMethods()) > 1 || !$this->hasGetMethod($route)) {
            return false;
        }

        // Exclude routes
        if (false !== stripos($route->getPath(), '}') // Routes with parameters
            || false !== stripos($routeName, '_api_') // API routes
            || false !== stripos($routeName, 'autocomplete') // Autocomplete Easyadmin routes
            || false !== stripos($routeName, 'batch') // Autocomplete Easyadmin routes
            || false !== stripos($routeName, 'oauth2') // OAuth2 routes
            || false !== stripos($routeName, 'connect') // OAuth2 connect & check routes
            || (!\in_array('ANY', $route->getMethods(), true) && !\in_array('GET', $route->getMethods(), true))
        ) {
            return false;
        }

        return true;
    }

    private function hasGetMethod(Route $route): bool
    {
        return (1 === \count($route->getMethods()) && \in_array('GET', $route->getMethods(), true))
            || 0 === \count($route->getMethods()); // No method defined 'ANY'
    }
}
