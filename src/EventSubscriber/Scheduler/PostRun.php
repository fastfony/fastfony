<?php

declare(strict_types=1);

namespace App\EventSubscriber\Scheduler;

use App\Repository\Scheduler\RunRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Scheduler\Event\PostRunEvent;

class PostRun implements EventSubscriberInterface
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
            PostRunEvent::class => 'onPostRun',
        ];
    }

    public function onPostRun(PostRunEvent $event): void
    {
        $run = $this->runRepository->findOneBy([
            'messageContextId' => $event->getMessageContext()->id,
            'runDate' => $event->getMessageContext()->triggeredAt->format('U'),
        ]);

        $run->setTerminated(true);

        $this->runRepository->save($run);
    }
}
