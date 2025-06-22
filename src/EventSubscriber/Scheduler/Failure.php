<?php

declare(strict_types=1);

namespace App\EventSubscriber\Scheduler;

use App\Repository\Scheduler\RunRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Scheduler\Event\FailureEvent;

class Failure implements EventSubscriberInterface
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
            FailureEvent::class => 'onFailure',
        ];
    }

    public function onFailure(FailureEvent $event): void
    {
        $run = $this->runRepository->findOneBy([
            'messageContextId' => $event->getMessageContext()->id,
            'runDate' => $event->getMessageContext()->triggeredAt->format('U'),
        ]);

        $run->setFailureOutput(serialize([
            'message' => $event->getError()->getMessage(),
            'trace' => $event->getError()->getTraceAsString(),
        ]));

        $this->runRepository->save($run);
    }
}
