<?php

declare(strict_types=1);

namespace App\EventSubscriber\Scheduler;

use App\Entity\Scheduler\Run;
use App\Repository\Scheduler\RunRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Scheduler\Event\PreRunEvent;

class PreRun implements EventSubscriberInterface
{
    public function __construct(
        private RunRepository $runRepository,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PreRunEvent::class => 'onPreRun',
        ];
    }

    public function onPreRun(PreRunEvent $event): void
    {
        $run = (new Run())
            ->setMessageContextId($event->getMessageContext()->id)
            ->setRunDate($event->getMessageContext()->triggeredAt->format('U'))
            ->setScheduler($event->getSchedule()::class)
            ->setTrigger($event->getMessageContext()->trigger::class)
            ->setInput(serialize($event->getMessage()))
        ;

        $this->runRepository->save($run);
    }
}
