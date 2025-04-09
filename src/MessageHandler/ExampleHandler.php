<?php

declare(strict_types=1);

namespace App\MessageHandler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Notification\Notification;

#[AsMessageHandler]
class ExampleHandler
{
    public function __invoke(Notification $notification): void
    {
        // Nothing here! It's just an example
        // More infos : https://symfony.com/doc/current/scheduler.html and
        // https://symfony.com/doc/current/messenger.html
    }
}
