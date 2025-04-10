<?php

declare(strict_types=1);

namespace App\EventListener\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class)]
class LoginSuccess
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        // We can be not enabled and successfully login only with login link
        if ($user instanceof User && !$user->isEnabled()) {
            // So we can enable the user here
            $user->setEnabled(true);
            $this->userRepository->save($user);
        }
    }
}
