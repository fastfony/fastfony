<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Scheduler;

use App\Pro\Entity\Scheduler\ProcessedMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Zenstruck\Messenger\Monitor\History\Model\Results;
use Zenstruck\Messenger\Monitor\Stamp\DescriptionStamp;
use Zenstruck\Messenger\Monitor\Stamp\MonitorStamp;

class ProcessedMessageTest extends TestCase
{
    private ProcessedMessage $processedMessage;

    protected function setUp(): void
    {
        $monitorStamp = $this->createMock(MonitorStamp::class);
        $monitorStamp->method('runId')
            ->willReturn(42);
        $monitorStamp->method('receivedAt')
            ->willReturn(new \DateTimeImmutable());
        $monitorStamp->method('finishedAt')
            ->willReturn(new \DateTimeImmutable());
        $monitorStamp->method('dispatchedAt')
            ->willReturn(new \DateTimeImmutable());

        $descriptionStamp = $this->createMock(DescriptionStamp::class);

        $redeliveryStamp = $this->createMock(RedeliveryStamp::class);
        $redeliveryStamp->method('getRetryCount')
            ->willReturn(1);

        $envelope = $this->createMock(Envelope::class);
        $envelope->method('last')
            ->willReturnOnConsecutiveCalls(
                $monitorStamp,
                $descriptionStamp,
                $redeliveryStamp,
            );

        $this->processedMessage = new ProcessedMessage(
            $envelope,
            $this->createMock(Results::class),
        );
    }

    public function testGetSetId(): void
    {
        $id = 42;
        $result = $this->processedMessage->setId($id);

        $this->assertSame($id, $this->processedMessage->id());
        $this->assertSame($this->processedMessage, $result);
    }
}
