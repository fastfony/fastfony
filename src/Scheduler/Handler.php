<?php

declare(strict_types=1);

namespace App\Scheduler;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Generator\MessageContext;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

class Handler
{
    /**
     * @param iterable<ScheduleProviderInterface> $schedules
     */
    public function __construct(
        #[AutowireIterator('scheduler.schedule_provider')]
        private iterable $schedules,
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function forceRun(string $recurringMessageId): bool
    {
        /** @var ScheduleProviderInterface $schedule */
        foreach ($this->schedules as $schedule) {
            if ($schedule->getSchedule()->getRecurringMessages()) {
                /** @var RecurringMessage $recurringMessage */
                foreach ($schedule->getSchedule()->getRecurringMessages() as $recurringMessage) {
                    if ($recurringMessage->getId() === $recurringMessageId) {
                        foreach ($recurringMessage->getProvider()->getMessages(
                            new MessageContext(
                                $recurringMessage->getId(),
                                $recurringMessage->getId(),
                                $recurringMessage->getTrigger(),
                                new \DateTimeImmutable(),
                                $recurringMessage->getTrigger()->getNextRunDate(new \DateTimeImmutable())
                            )
                        ) as $message) {
                            try {
                                $this->bus->dispatch($message);

                                return true;
                            } catch (\Exception) {
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }
}
