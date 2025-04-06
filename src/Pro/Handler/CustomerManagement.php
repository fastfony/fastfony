<?php

declare(strict_types=1);

namespace App\Pro\Handler;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Stripe\StripeObject;
use Symfony\Bundle\SecurityBundle\Security;

class CustomerManagement
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Security $security,
    ) {
    }

    public function findOrCreate(
        ?StripeObject $customerDetails,
        ?string $stripeCustomerId,
    ): User {
        /** @var User $customer */
        $customer = $this->security->getUser();
        $customerDetails = $customerDetails->toArray();
        // If user is not connected, we try to find it by stripeCustomerId or email
        if (null === $customer) {
            $customer = $this->userRepository->findByStripeIdOrEmail(
                $stripeCustomerId,
                $customerDetails['email']
            );

            if (null === $customer) {
                $customer = $this->userRepository->create($customerDetails['email']);
            }

            $this->connectUserIfNotAlreadyConnected($customer);
        }

        if ($stripeCustomerId) {
            $customer->appendStripeCustomerId($stripeCustomerId);
        }

        $customer->setName($customerDetails['name']);

        return $customer;
    }

    public function connectUserIfNotAlreadyConnected(User $user): void
    {
        if (!$this->security->getUser()) {
            $this->security->login(
                $user,
                'security.authenticator.remember_me.main',
            );
        }
    }
}
