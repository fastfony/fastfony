<?php

declare(strict_types=1);

namespace App\Tests\Functional\Pro\Admin;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SchedulerTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($user);

        $client->request('GET', '/admin?routeName=admin_scheduler_index');
        $this->assertResponseIsSuccessful();

        $client->clickLink('Force run now');
        $this->assertSelectorExists('#flash-messages .alert.alert-success');
    }
}
