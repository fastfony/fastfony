<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Group;
use App\Entity\User\Profile;
use App\Entity\User\Role;
use App\Entity\User\User;
use App\Tests\Unit\Entity\ToStringTestTrait;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    use ToStringTestTrait;

    public function testToString(): void
    {
        $this->toStringTest(
            User::class,
            'email',
        );
    }

    public function testGetUserIdentifierTest(): void
    {
        $user = (new User())
            ->setEmail('userIdentifier')
        ;

        $this->assertSame('userIdentifier', $user->getUserIdentifier());
    }

    public function testGetRoles(): void
    {
        $user = (new User())
            ->addGroup(
                (new Group())
                    ->addRole(
                        (new Role())
                            ->setName('ROLE_TEST')
                    )
            );

        $this->assertContains('ROLE_TEST', $user->getRoles());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->eraseCredentials();

        self::expectNotToPerformAssertions();
    }

    public function testSetProfile(): void
    {
        $user = new User();
        $profile = (new Profile())
            ->setUser($user)
        ;

        $user2 = (new User())
            ->setProfile($profile)
        ;

        $this->assertNull($user->getProfile());
        $this->assertSame($profile, $user2->getProfile());
    }
}
