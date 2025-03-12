<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCrudTest extends WebTestCase
{
    public function testSendLoginLinkEmail(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        $client->request('GET', '/admin/user-crud/'.$user->getId().'/send-login-link');
        $this->assertEmailCount(1);
    }
}
