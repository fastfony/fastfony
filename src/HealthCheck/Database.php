<?php

declare(strict_types=1);

namespace App\HealthCheck;

use Doctrine\ORM\EntityManagerInterface;

class Database
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function check(): bool
    {
        try {
            $this->entityManager->getConnection()->connect();
        } catch (\Exception) {
            return false;
        }

        return true;
    }
}
