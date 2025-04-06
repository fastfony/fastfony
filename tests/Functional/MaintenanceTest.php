<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\Parameter\ParameterRepository;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MaintenanceTest extends WebTestCase
{
    public function testMaintenancePageWhenDisabled(): void
    {
        $this->changeValueMaintenanceModeParamater('0');
        $client = static::createClient();
        $client->request('GET', '/maintenance');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        // By default, maintenance mode is disabled, redirect to homepage
        $this->assertSelectorTextNotContains('h1', 'Maintenance');
    }

    public function testMaintenancePageWhenEnabled(): void
    {
        $this->changeValueMaintenanceModeParamater('1');
        $client = static::createClient();

        $client->request('GET', '/');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Under Maintenance');

        $superAdmin = self::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'superadmin@fastfony.com']);

        $client->loginUser($superAdmin);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        // But user is admin, so he can access the page, he don't see the maintenance message
        $this->assertSelectorTextNotContains('h1', 'Maintenance');
    }

    protected function tearDown(): void
    {
        $this->changeValueMaintenanceModeParamater('0');
    }

    private function changeValueMaintenanceModeParamater(string $value): void
    {
        self::ensureKernelShutdown();
        self::bootKernel();

        // Enable maintenance mode
        $parameterRepository = self::getContainer()
            ->get(ParameterRepository::class);

        $maintenanceModeParameter = $parameterRepository->findOneBy(['key' => 'FASTFONY_MAINTENANCE_MODE']);
        $maintenanceModeParameter->setValue($value);
        $parameterRepository->save($maintenanceModeParameter);

        self::ensureKernelShutdown();
    }
}
