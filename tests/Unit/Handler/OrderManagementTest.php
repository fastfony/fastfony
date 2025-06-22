<?php

declare(strict_types=1);

namespace App\Tests\Unit\Handler;

use App\Entity\Order;
use App\Entity\Product\Price;
use App\Entity\User\User;
use App\Handler\CustomerManagement;
use App\Handler\OrderManagement;
use App\Repository\OrderRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;

final class OrderManagementTest extends TestCase
{
    private OrderManagement $orderManagement;
    private CustomerManagement|MockObject $customerManagement;
    private OrderRepository|MockObject $orderRepository;

    protected function setUp(): void
    {
        $this->customerManagement = $this->createMock(CustomerManagement::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->orderManagement = new OrderManagement(
            $this->customerManagement,
            $this->orderRepository,
        );
    }

    public function testCreate(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('toArray')
            ->willReturn([
                'id' => 'session_id',
                'payment_intent' => 'pi_id',
                'subscription' => null,
                'payment_status' => 'paid',
                'status' => 'complete',
                'customer_details' => [],
                'amount_subtotal' => 10000,
                'amount_total' => 12000,
            ]);

        $this->customerManagement->expects($this->once())
            ->method('findOrCreate')
            ->willReturn(new User());

        $this->orderRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->orderRepository->expects($this->once())
            ->method('save');

        $this->orderManagement
            ->create(
                $this->createMock(Price::class),
                $session,
            );
    }

    public function testUpdate(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('toArray')
            ->willReturn([
                'id' => 'session_id',
                'payment_intent' => 'pi_id',
                'subscription' => null,
                'payment_status' => 'paid',
                'status' => 'complete',
                'customer_details' => [],
                'amount_subtotal' => 10000,
                'amount_total' => 12000,
            ]);

        $this->customerManagement->expects($this->once())
            ->method('findOrCreate')
            ->willReturn(new User());

        $this->orderRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new Order());

        $this->orderRepository->expects($this->once())
            ->method('save');

        $this->orderManagement
            ->create(
                $this->createMock(Price::class),
                $session,
            );
    }
}
