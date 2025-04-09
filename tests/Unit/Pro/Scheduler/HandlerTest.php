<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Scheduler;

use App\Pro\Scheduler\Handler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\Trigger\MessageProviderInterface;

final class HandlerTest extends TestCase
{
    private function createHandlerWithMocks(
        ?MessageBusInterface $bus = null,
    ): Handler {
        $scheduleProvider = $this->createMock(ScheduleProviderInterface::class);
        $bus = $bus ?? $this->createMock(MessageBusInterface::class);
        $recurringMessage = $this->createMock(RecurringMessage::class);
        $provider = $this->createMock(MessageProviderInterface::class);
        $schedule = $this->createMock(Schedule::class);

        $recurringMessage->method('getId')->willReturn('id');
        $provider->method('getMessages')->willReturn([$this->createMock(Notification::class)]);
        $recurringMessage->method('getProvider')->willReturn($provider);
        $schedule->method('getRecurringMessages')->willReturn([$recurringMessage]);
        $scheduleProvider->method('getSchedule')->willReturn($schedule);

        return new Handler([$scheduleProvider], $bus);
    }

    public function testForceRun(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects(self::once())->method('dispatch');

        $handler = $this->createHandlerWithMocks($bus);

        $this->assertTrue($handler->forceRun('id'));
    }

    public function testFailedForceRun(): void
    {
        $handler = new Handler([], $this->createMock(MessageBusInterface::class));

        $this->assertFalse($handler->forceRun('id'));
    }

    public function testForceRunWithException(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects(self::once())->method('dispatch')->willThrowException(new \Exception());

        $handler = $this->createHandlerWithMocks($bus);

        $this->assertFalse($handler->forceRun('id'));
    }
}
