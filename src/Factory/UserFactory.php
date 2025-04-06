<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;

class UserFactory
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function fromGoogle(GoogleUser $googleUser): User
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $googleUser->getEmail()]);
        if ($existingUser) {
            return $existingUser;
        }

        return $this->userRepository->create($googleUser->getEmail());
    }

    public function fromGithub(GithubResourceOwner $githubUser): User
    {
        // You can also fetch the user from the database by githubId ($fetchUser->getId())
        // or github Nickname ($fetchUser->getNickname())
        $existingUser = $this->userRepository->findOneBy(['email' => $githubUser->getEmail()]);
        if ($existingUser) {
            return $existingUser;
        }

        return $this->userRepository->create($githubUser->getEmail());
    }
}
