<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Product;

use App\Pro\Entity\Order;
use App\Pro\Entity\Product\Price;
use App\Pro\Enum\RecurringInterval;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class PriceTest extends TestCase
{
    private Price $price;

    protected function setUp(): void
    {
        $this->price = new Price();
    }

    public function testSetRecurringInterval(): void
    {
        $interval = RecurringInterval::Month;
        $result = $this->price->setRecurringInterval($interval);

        $this->assertSame($interval, $this->price->getRecurringInterval());
        $this->assertSame($this->price, $result);

        $result = $this->price->setRecurringInterval(null);

        $this->assertNull($this->price->getRecurringInterval());
        $this->assertSame($this->price, $result);
    }

    public function testRemoveOrder(): void
    {
        $order = $this->createMock(Order::class);
        $this->price->addOrder($order);

        $order->expects($this->once())
              ->method('getPrice')
              ->willReturn($this->price);

        $order->expects($this->once())
              ->method('setPrice')
              ->with(null);

        $result = $this->price->removeOrder($order);

        $this->assertSame($this->price, $result);
        $this->assertCount(0, $this->price->getOrders());
    }

    public function testRemoveOrderWhenPriceIsNotThis(): void
    {
        $order = $this->createMock(Order::class);
        $otherPrice = new Price();
        $this->price->addOrder($order);

        $order->expects($this->once())
              ->method('getPrice')
              ->willReturn($otherPrice);

        $order->expects($this->never())
              ->method('setPrice');

        $this->price->removeOrder($order);

        $this->assertCount(0, $this->price->getOrders());
    }

    public function testValidateRecurringFieldsAllSet(): void
    {
        $this->price->setRecurringInterval(RecurringInterval::Month);
        $this->price->setRecurringIntervalCount(1);
        $this->price->setRecurringTrialPeriodDays(14);

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->never())
                ->method('buildViolation');

        $this->price->validateRecurringFields($context);
    }

    public function testValidateRecurringFieldsNoneSet(): void
    {
        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->never())
                ->method('buildViolation');

        $this->price->validateRecurringFields($context);
    }

    public function testValidateRecurringFieldsPartiallySet(): void
    {
        $this->price->setRecurringInterval(RecurringInterval::Month);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->expects($this->once())
                        ->method('atPath')
                        ->with('recurringInterval')
                        ->willReturnSelf();
        $violationBuilder->expects($this->once())
                        ->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->once())
                ->method('buildViolation')
                ->with('All recurring fields must be set together or left empty.')
                ->willReturn($violationBuilder);

        $this->price->validateRecurringFields($context);
    }
}
