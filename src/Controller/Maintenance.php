<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Parameter\ParameterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Maintenance extends AbstractController
{
    public function __construct(
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    #[Route('/maintenance', name: 'maintenance', methods: ['GET'])]
    public function __invoke(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $maintenanceMode = $this->parameterRepository->findOneBy(['key' => 'FASTFONY_MAINTENANCE_MODE']);
            if (!$maintenanceMode || '1' !== $maintenanceMode->getValue()) {
                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('maintenance.html.twig');
    }
}
