<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Parameter\ParameterRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class Maintenance extends AbstractController
{
    public function __construct(
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[Route('/maintenance', name: 'maintenance', methods: ['GET'])]
    #[Template('maintenance.html.twig')]
    public function __invoke(): array|RedirectResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $maintenanceMode = $this->parameterRepository->findOneBy(['key' => 'FASTFONY_MAINTENANCE_MODE']);
            if (!$maintenanceMode || '1' !== $maintenanceMode->getValue()) {
                return $this->redirectToRoute('homepage');
            }
        }

        return [];
    }
}
