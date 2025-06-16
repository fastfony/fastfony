<?php

declare(strict_types=1);

namespace App\HealthCheck;

use App\Repository\Parameter\ParameterRepository;
use Doctrine\ORM\EntityManagerInterface;

class Database
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    public function check(): bool
    {
        try {
            $this->entityManager->getConnection()->getNativeConnection();

            if (!$this->parameterRepository->findOneBy(['key' => 'MAILER_SENDER'])) {
                return false;
            }
        } catch (\Exception) {
            return false;
        }

        return true;
    }
}
