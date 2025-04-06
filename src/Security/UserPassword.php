<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\RequestPassword;
use App\Repository\User\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPassword
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function change(RequestPassword $requestPassword, string $plaintextPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $requestPassword->getUser(),
            $plaintextPassword
        );

        $this->userRepository->updatePassword($requestPassword->getUser(), $hashedPassword);
        $this->userRepository->remove($requestPassword);
    }
}
