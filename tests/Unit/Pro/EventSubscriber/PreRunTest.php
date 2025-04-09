<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\EventSubscriber;

use App\Pro\EventSubscriber\Scheduler\PreRun;
use App\Pro\Repository\Scheduler\RunRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Scheduler\Event\PreRunEvent;
use Symfony\Component\Scheduler\Generator\MessageContext;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;

final class PreRunTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            PreRunEvent::class => 'onPreRun',
        ];

        $this->assertSame($expectedEvents, PreRun::getSubscribedEvents());
    }

    public function testOnPostRun(): void
    {
        $messageContext = $this->createMock(MessageContext::class); // Thanks to dg/bypass-finals
        $messageContext->id = 'id';
        $messageContext->triggeredAt = new \DateTimeImmutable();
        $messageContext->trigger = $this->createMock(CronExpressionTrigger::class);

        $event = $this->createMock(PreRunEvent::class);
        $event->method('getMessageContext')
            ->willReturn($messageContext);

        $runRepository = $this->createMock(RunRepository::class);
        $runRepository->expects($this->once())
            ->method('save');

        $subscriber = new PreRun($runRepository);
        $subscriber->onPreRun($event);
    }
}
