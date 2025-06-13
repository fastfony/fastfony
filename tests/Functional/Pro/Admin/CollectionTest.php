<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin\GroupRole;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CollectionTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        $client->request('GET', '/admin?routeName=admin_record_collection_index');
        $this->assertResponseIsSuccessful();
    }
}
