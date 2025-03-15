<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\Parameter\ParameterRepository;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use Fastfony\LicenceBundle\Security\LicenceChecker;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step3
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoginLink $loginLink,
        #[Autowire(service: 'fastfony_licence.security.licence_checker')]
        private readonly LicenceChecker $licenceChecker,
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    public function do(
        FormInterface $installationForm,
        Request $request,
    ): bool {
        $success = false;
        $installationForm->handleRequest($request);

        if ($installationForm->getData()['autoGenerateLicenceKey']) {
            $installationForm->getData()['autoGenerateLicenceKey'] =
                $this->licenceChecker->generate($installationForm->getData()['email']);
        }

        if ($installationForm->isSubmitted() && $installationForm->isValid()) {
            $installationForm->getData()['licenceKey'];
            $this->parameterRepository->findOneBy(['key' => 'FASTFONY_LICENCE_KEY'])
                ->setValue($installationForm->getData()['licenceKey']);

            try {
                // We create the first admin user
                $user = $this->userRepository->createSuperAdmin(
                    $installationForm->getData()['email'],
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
