<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Entity\User\User;
use Symfony\Component\Notifier\Notification\Notification;

class AppNotification extends Notification
{
    public function __construct(
        public User $recipient,
        string $subject,
        string $content,
        string $importance = Notification::IMPORTANCE_HIGH,
        ?string $emoji = null,
    ) {
        parent::__construct($subject, ['chat/app']);
        parent::content($content);
        parent::importance($importance);
        if (null !== $emoji) {
            parent::emoji($emoji);
        }
    }
}
