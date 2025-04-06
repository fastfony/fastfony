<?php

declare(strict_types=1);

namespace App\Scheduler;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
final class Example implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        // This schedule and recurring message are just an example
        // An exemple of MessageHandler is also available in src/MessageHandler/ExampleHandler.php
        return (new Schedule())
            ->add(
                RecurringMessage::every('1 minute', new Notification('Hello world', ['email'])),
            )
            ->stateful($this->cache)
        ;
    }
}
