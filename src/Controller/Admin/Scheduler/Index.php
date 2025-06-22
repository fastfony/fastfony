<?php

declare(strict_types=1);

namespace App\Controller\Admin\Scheduler;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

class Index extends AbstractController
{
    /**
     * @param iterable<ScheduleProviderInterface> $schedules
     */
    public function __construct(
        #[AutowireIterator('scheduler.schedule_provider')]
        private iterable $schedules,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[Route('/admin/scheduler', name: 'admin_scheduler_index')]
    #[Template('admin/scheduler/index.html.twig')]
    public function __invoke(): array
    {
        $scheduleMessages = [];
        /** @var ScheduleProviderInterface $schedule */
        foreach ($this->schedules as $schedule) {
            if ($schedule->getSchedule()->getRecurringMessages()) {
                /** @var RecurringMessage $recurringMessage */
                foreach ($schedule->getSchedule()->getRecurringMessages() as $recurringMessage) {
                    $scheduleMessages[] = [
                        'trigger' => $recurringMessage->getTrigger(),
                        'provider' => $recurringMessage->getProvider(),
                        'scheduler' => $schedule::class,
                        'nextRunDate' => $recurringMessage->getTrigger()->getNextRunDate(new \DateTimeImmutable()),
                        'id' => $recurringMessage->getId(),
                    ];
                }
            }
        }

        return [
            'schedule_messages' => $scheduleMessages,
        ];
    }
}
