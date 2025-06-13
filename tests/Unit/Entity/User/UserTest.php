<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Group;
use App\Entity\User\Profile;
use App\Entity\User\Role;
use App\Entity\User\Stripe\CustomerId;
use App\Entity\User\User;
use App\Tests\Unit\Entity\ToStringTestTrait;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
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

    public function testHasStripeCustomerId(): void
    {
        $user = new User();
        $user->appendStripeCustomerId('customer_1');

        $this->assertTrue($user->hasStripeCustomerId('customer_1'));
        $this->assertFalse($user->hasStripeCustomerId('customer_2'));
    }

    public function testGetLastStripeCustomerId(): void
    {
        $user = new User();
        $user->appendStripeCustomerId('customer_1');
        $user->appendStripeCustomerId('customer_2');

        $this->assertSame('customer_2', $user->getLastStripeCustomerId());
    }

    public function testAppendStripeCustomerId(): void
    {
        $user = new User();
        $user->appendStripeCustomerId('customer_1');
        $user->appendStripeCustomerId('customer_1'); // Duplicate

        $this->assertCount(1, $user->getStripeCustomerIds());
        $this->assertTrue($user->hasStripeCustomerId('customer_1'));
    }

    public function testRemoveStripeCustomerId(): void
    {
        $user = new User();
        $customerId = (new CustomerId())->setId('customer_1')->setUser($user);
        $user->addStripeCustomerId($customerId);

        $this->assertTrue($user->hasStripeCustomerId('customer_1'));

        $user->removeStripeCustomerId($customerId);

        $this->assertFalse($user->hasStripeCustomerId('customer_1'));
    }
}
