<?php

declare(strict_types=1);

namespace App\Controller\Admin\Scheduler;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/admin/scheduler', name: 'admin_scheduler_index')]
    public function __invoke(): Response
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

        return $this->render(
            'admin/scheduler/index.html.twig',
            [
                'schedule_messages' => $scheduleMessages,
            ],
        );
    }
}
