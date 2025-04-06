<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\EventSubscriber;

use App\Pro\Entity\Scheduler\Run;
use App\Pro\EventSubscriber\Scheduler\Failure;
use App\Pro\Repository\Scheduler\RunRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Scheduler\Event\FailureEvent;
use Symfony\Component\Scheduler\Generator\MessageContext;

final class FailureTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            FailureEvent::class => 'onFailure',
        ];

        $this->assertSame($expectedEvents, Failure::getSubscribedEvents());
    }

    public function testOnFailure(): void
    {
        $messageContext = $this->createMock(MessageContext::class); // Thanks to dg/bypass-finals
        $messageContext->id = 'id';
        $messageContext->triggeredAt = new \DateTimeImmutable();

        $event = $this->createMock(FailureEvent::class);
        $event->method('getMessageContext')
            ->willReturn($messageContext);

        $runRepository = $this->createMock(RunRepository::class);
        $subscriber = new Failure($runRepository);

        $run = $this->createMock(Run::class);
        $run->expects($this->once())
            ->method('setFailureOutput');

        $runRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($run);

        $runRepository->expects($this->once())
            ->method('save');
        $subscriber->onFailure($event);
    }
}
