<?php

declare(strict_types=1);

namespace App\EventListener;

use App\HealthCheck\All;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: RequestEvent::class)]
readonly class InstallationCheck
{
    private const EXCLUDE_ROUTES = [
        'installation',
        'installation_step',
        '_profiler',
        '_profiler_home',
        '_wdt',
        '_wdt_stylesheet',
    ];

    public function __construct(
        private RouterInterface $router,
        private All $allHealthChecks,
        private UserRepository $userRepository,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // We check if the installation is already done
        $users = 0;
        try {
            // If there is already an enabled user, we don't need to check other sensors
            $users = $this->userRepository->countEnabled();
            if (0 < $users) {
                return;
            }
        } catch (ConnectionException|TableNotFoundException $e) {
            // If the database is not created, a "normal" exception is thrown, we catch and continue
        }

        if (\in_array($event->getRequest()->attributes->get('_route'), self::EXCLUDE_ROUTES, true)) {
            return;
        }

        $checks = $this->allHealthChecks->checks();

        // If one of the checks is not passed or if there haven't a first user, redirect to the installation page
        if (\in_array(false, $checks, true) || 0 === $users) {
            // $event->setResponse(new RedirectResponse($this->router->generate('installation')));
        }
    }
}
