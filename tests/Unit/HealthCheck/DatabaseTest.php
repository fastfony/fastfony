<?php

declare(strict_types=1);

namespace App\Tests\Unit\HealthCheck;

use App\HealthCheck\Database;
use App\Repository\Parameter\ParameterRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    public function testCheck(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $parameterRepository = $this->createMock(ParameterRepository::class);
        $database = new Database($entityManager, $parameterRepository);

        $entityManager->method('getConnection')
            ->willThrowException(new ConnectionException());

        $this->assertFalse($database->check());
    }
}
