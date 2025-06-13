<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin\GroupRole;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MatrixTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        $client->request('GET', '/admin?routeName=admin_group_role_matrix');
        $this->assertResponseIsSuccessful();
    }
}
