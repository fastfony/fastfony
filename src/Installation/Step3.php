<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\UserRepository;
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
    ): void {
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            // We create the first admin user
            $user = $this->userRepository->createSuperAdmin($loginForm->getData()['email']);
            // We send a email login link
            $this->loginLink->send($user);
        }
    }
}
