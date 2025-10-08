<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserCrudTest extends WebTestCase
{
    public function testSendLoginLinkEmail(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        $client->request(
            'GET',
            '/admin/user-crud/'.$user->getId().'/send-login-link',
        );
        $this->assertEmailCount(1);
    }

    public function testSwitchUser(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['enabled' => true]);
        $otherUser = $userRepository->findOneBy(['enabled' => true, 'id' => $user->getId() + 1]);

        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        // TODO : pour le moment ça passe ici
        if (null === $otherUser) {
            $this->markTestSkipped('No other enabled user found for switchUser test.');
        }

        $client->request(
            'GET',
            '/admin/user-crud/' . $otherUser->getId() . '/switch-user',
        );

        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect(), 'Response should be a redirect');
        $this->assertStringContainsString(
            (string) $otherUser->getUserIdentifier(),
            (string) $response->headers->get('location'),
            'Redirect location should contain switched user identifier'
        );
    }
}
