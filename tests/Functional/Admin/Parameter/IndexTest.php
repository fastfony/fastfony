<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin\Parameter;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class IndexTest extends WebTestCase
{
    public function testToggleFeature(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($user);

        $client->request('GET', '/admin?routeName=admin_parameters');
        $this->assertSelectorTextContains('h1', 'Settings');
    }
}
