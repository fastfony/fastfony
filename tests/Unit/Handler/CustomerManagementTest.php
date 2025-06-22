<?php

declare(strict_types=1);

namespace App\Tests\Unit\Handler;

use App\Entity\User\User;
use App\Handler\CustomerManagement;
use App\Repository\User\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stripe\StripeObject;
use Symfony\Bundle\SecurityBundle\Security;

final class CustomerManagementTest extends TestCase
{
    private CustomerManagement $customerManagement;
    private UserRepository|MockObject $userRepository;
    private Security|MockObject $security;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->security = $this->createMock(Security::class);

        $this->customerManagement = new CustomerManagement(
            $this->userRepository,
            $this->security,
        );
    }

    public function testFindFromSecurity(): void
    {
        $user = $this->createMock(User::class);

        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $user->expects($this->once())
            ->method('appendStripeCustomerId');

        $this->customerManagement->findOrCreate(
            $this->getCustomerDetailsMock(),
            'stripeCustomerId'
        );
    }

    public function testFindFromStripeIdOrEmail(): void
    {
        $this->security->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn(null);

        $user = $this->createMock(User::class);

        $this->userRepository->expects($this->once())
            ->method('findByStripeIdOrEmail')
            ->willReturn($user);

        $user->expects($this->once())
            ->method('appendStripeCustomerId');

        // Automatically login
        $this->security->expects($this->once())
            ->method('login');

        $this->customerManagement->findOrCreate(
            $this->getCustomerDetailsMock(),
            'stripeCustomerId'
        );
    }

    public function testCreate(): void
    {
        $this->security->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn(null);

        $user = $this->createMock(User::class);

        $this->userRepository->expects($this->once())
            ->method('findByStripeIdOrEmail')
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('create')
            ->willReturn($user);

        $user->expects($this->once())
            ->method('appendStripeCustomerId');

        // Automatically login
        $this->security->expects($this->once())
            ->method('login');

        $this->customerManagement->findOrCreate(
            $this->getCustomerDetailsMock(),
            'stripeCustomerId'
        );
    }

    public function testConnectUserIfNotAlreadyConnected(): void
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->security->expects($this->once())
            ->method('login');

        $this->customerManagement->connectUserIfNotAlreadyConnected(new User());
    }

    public function testNotConnectUserIfAlreadyConnected(): void
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn(new User());

        $this->security->expects($this->never())
            ->method('login');

        $this->customerManagement->connectUserIfNotAlreadyConnected(new User());
    }

    private function getCustomerDetailsMock(): MockObject|StripeObject
    {
        $customerDetails = $this->createMock(StripeObject::class);
        $customerDetails->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'email' => 'email@domain.com',
                'name' => 'name',
            ])
        ;

        return $customerDetails;
    }
}
