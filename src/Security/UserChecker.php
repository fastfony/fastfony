<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // If it's not a user with only login link (no password) : check if the user is enabled
        if (null !== $user->getPassword() && !$user->isEnabled()) {
            throw new CustomUserMessageAccountStatusException('Inactive account');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Do nothing
    }
}
