<?php

declare(strict_types=1);

namespace App\Tests\Unit\HealthCheck;

use App\HealthCheck\Database;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testCheck(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $database = new Database($entityManager);

        $entityManager->method('getConnection')
            ->willThrowException(new ConnectionException());

        $this->assertFalse($database->check());
    }
}
