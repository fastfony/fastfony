<?php

declare(strict_types=1);

namespace App\Tests\Unit\EnvProcessor;

use App\Entity\Parameter\Parameter;
use App\EnvProcessor\Database;
use App\Repository\Parameter\ParameterRepository;
use Doctrine\DBAL\ConnectionException;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testGetProvidedTypes(): void
    {
        $this->assertSame(
            ['database' => 'string'],
            Database::getProvidedTypes(),
        );
    }

    public function testGetEnv(): void
    {
        $parameterRepository = $this->createMock(ParameterRepository::class);
        $databaseEnvProcessor = new Database($parameterRepository);

        $this->assertNull($this->callGetEnv($databaseEnvProcessor));

        $parameterRepository->method('findOneBy')
            ->willReturn(
                (new Parameter())
                ->setValue('noreply@domain.tld')
            );

        $this->assertEquals(
            'noreply@domain.tld',
            $this->callGetEnv($databaseEnvProcessor),
        );

        $parameterRepository->method('findOneBy')
            ->willThrowException(new ConnectionException());

        $this->assertNull($this->callGetEnv($databaseEnvProcessor));
    }

    private function callGetEnv(Database $databaseEnvProcessor): ?string
    {
        return $databaseEnvProcessor->getEnv(
            'database',
            'MAILER_FROM',
            fn () => null,
        );
    }
}
