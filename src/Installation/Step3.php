<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\Parameter\ParameterRepository;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step3
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoginLink $loginLink,
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    /** @phpstan-ignore missingType.generics */
    public function do(
        FormInterface $form,
        Request $request,
    ): bool {
        $success = false;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->parameterRepository->findOneBy(['key' => 'FASTFONY_LICENSE_KEY'])
                ->setValue($form->getData()['licenseKey']);

            try {
                // We set the MAILER_SENDER parameter
                $this->parameterRepository->findOneBy(['key' => 'MAILER_SENDER'])
                    ->setValue($form->getData()['mailerSender']);

                // We create the first admin user
                $user = $this->userRepository->createSuperAdmin(
                    $form->getData()['email'],
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
