<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository\User;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
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
}
