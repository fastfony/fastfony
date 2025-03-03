<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step3
{
    public function __construct(
        private UserRepository $userRepository,
        private LoginLink $loginLink,
    ) {
    }

    public function do(
        FormInterface $loginForm,
        Request $request,
    ): bool {
        $success = false;
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            try {
                // We create the first admin user
                $user = $this->userRepository->createSuperAdmin(
                    $loginForm->getData()['email'],
                    true,
                );
            } catch (\Exception $e) {
                return false;
            }

            // We send a email login link
            if ($this->loginLink->send($user)) {
                $success = true;
            }
        }

        return $success;
    }
}
