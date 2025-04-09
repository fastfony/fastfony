<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Repository\Parameter\ParameterRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: RequestEvent::class)]
readonly class MaintenanceMode
{
    public function __construct(
        private readonly ParameterRepository $parameterRepository,
        private readonly RouterInterface $router,
        private readonly Security $security,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $maintenanceMode = $this->parameterRepository->findOneBy(['key' => 'FASTFONY_MAINTENANCE_MODE']);
        if ($maintenanceMode && '1' === $maintenanceMode->getValue()
            && 'maintenance' !== $event->getRequest()->attributes->get('_route')) {
            $event->setResponse(new RedirectResponse($this->router->generate('maintenance')));
        }
    }
}
