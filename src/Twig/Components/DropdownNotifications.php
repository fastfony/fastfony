<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/notifications_dropdown.html.twig')]
class DropdownNotifications
{
    /**
     * @var array<int, mixed>
     */
    public array $notifications = [];

    public function __construct(
        private readonly Security $security,
    ) {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $this->notifications = $user->getAppChannel()->getMessages()->toArray();
        }
    }
}
