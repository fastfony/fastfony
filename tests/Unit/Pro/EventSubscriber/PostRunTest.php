<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\EventSubscriber;

use App\Pro\Entity\Scheduler\Run;
use App\Pro\EventSubscriber\Scheduler\PostRun;
use App\Pro\Repository\Scheduler\RunRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Scheduler\Event\PostRunEvent;
use Symfony\Component\Scheduler\Generator\MessageContext;

class PostRunTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            PostRunEvent::class => 'onPostRun',
        ];

        $this->assertSame($expectedEvents, PostRun::getSubscribedEvents());
    }

    public function testOnPostRun(): void
    {
        $messageContext = $this->createMock(MessageContext::class); // Thanks to dg/bypass-finals
        $messageContext->id = 'id';
        $messageContext->triggeredAt = new \DateTimeImmutable();

        $event = $this->createMock(PostRunEvent::class);
        $event->method('getMessageContext')
            ->willReturn($messageContext);

        $runRepository = $this->createMock(RunRepository::class);
        $subscriber = new PostRun($runRepository);

        $run = $this->createMock(Run::class);
        $run->expects($this->once())
            ->method('setTerminated');

        $runRepository->method('findOneBy')
            ->willReturn($run);

        $runRepository->expects($this->once())
            ->method('save');
        $subscriber->onPostRun($event);
    }
}
