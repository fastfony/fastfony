<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\Parameter\ParameterRepository;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use Fastfony\LicenseBundle\Security\LicenseChecker;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step3
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoginLink $loginLink,
        #[Autowire(service: 'fastfony_license.security.license_checker')]
        private readonly LicenseChecker $licenseChecker,
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    public function do(
        FormInterface $form,
        Request $request,
    ): bool {
        $success = false;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()['autoGenerateLicenseKey']) {
                $form->getData()['licenseKey'] =
                    $this->licenseChecker->generate($form->getData()['email']);
            }

            $form->getData()['licenseKey'];
            $this->parameterRepository->findOneBy(['key' => 'FASTFONY_LICENSE_KEY'])
                ->setValue($form->getData()['licenseKey']);

            try {
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
