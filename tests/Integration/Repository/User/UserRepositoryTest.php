<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    public function testCreateSuperAdmin(): void
    {
        $superAdmin = $this->userRepository->createSuperAdmin(
            'superadmin2@fastfony.com',
            true,
        );

        $this->assertNotNull($superAdmin->getId());
        $this->userRepository->remove($superAdmin);
    }

    public function testCreate(): void
    {
        $user = $this->userRepository->create('user@fastfony.com');

        $this->assertNotNull($user->getId());
        $this->userRepository->remove($user);
    }

    public function testUpdatePassword(): void
    {
        $email = 'test-update-password@example.com';
        $user = new User();
        $user->setEmail($email);

        $this->userRepository->save($user);

        $oldPassword = 'old-hashed-password';
        $user->setPassword($oldPassword);
        $newPassword = 'new-hashed-password';

        $this->userRepository->updatePassword($user, $newPassword);
        $updatedUser = $this->userRepository->findOneBy(['email' => $email]);

        $this->assertNotNull($updatedUser);
        $this->assertEquals($newPassword, $updatedUser->getPassword());
        $this->assertNotEquals($oldPassword, $updatedUser->getPassword());
    }

    public function testUpdatePasswordThrowsExceptionForUnsupportedUser(): void
    {
        // We create an object that implements PasswordAuthenticatedUserInterface but is not User
        $unsupportedUser = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): string
            {
                return 'some-password';
            }
        };

        // Vérifier que l'exception est bien lancée
        $this->expectException(UnsupportedUserException::class);
        $this->userRepository->updatePassword($unsupportedUser, 'new-password');
    }

    public function testFindByStripeIdOrEmailWithBothNull(): void
    {
        $result = $this->userRepository->findByStripeIdOrEmail(null, null);
        $this->assertNull($result);
    }

    public function testFindByStripeIdOrEmailWithEmail(): void
    {
        $result = $this->userRepository->findByStripeIdOrEmail(null, 'superadmin@fastfony.com');
        $this->assertInstanceOf(User::class, $result);
    }

    public function testFindByStripeIdOrEmailWithStripeId(): void
    {
        $result = $this->userRepository->findByStripeIdOrEmail('stripe-customer-id', null);
        $this->assertInstanceOf(User::class, $result);
    }

    protected function tearDown(): void
    {
        // We clean up the user created for the password update test
        $user = $this->userRepository->findOneBy(['email' => 'test-update-password@example.com']);
        if ($user) {
            $this->userRepository->remove($user);
        }

        parent::tearDown();
    }
}
