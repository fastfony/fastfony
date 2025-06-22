<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Form\RequestPasswordFormType;
use App\Repository\User\UserRepository;
use App\Security\ResetPasswordLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForgotPassword extends AbstractController
{
    public function __construct(
        private readonly ResetPasswordLink $userPassword,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function __invoke(Request $request): Response
    {
        $requestPasswordForm = $this->createForm(RequestPasswordFormType::class);

        if ($request->isMethod('POST')) {
            $requestPasswordForm->handleRequest($request);

            if ($requestPasswordForm->isSubmitted() && $requestPasswordForm->isValid()) {
                $email = $requestPasswordForm->get('email')->getData();
                $user = $this->userRepository->findOneBy([
                    'email' => $email,
                    'enabled' => true,
                ]);

                if (null !== $user) {
                    if (!$this->userPassword->send($user)) {
                        $this->addFlash(
                            'error',
                            'flash.login_link_sent.error',
                        );
                    }
                }

                // We always redirect to the confirm message to avoid user enumeration
                return $this->render(
                    'security/forgot_password.html.twig',
                    [],
                    new Response(null, Response::HTTP_SEE_OTHER), // For Turbo drive compatibility
                );
            }
        }

        return $this->render(
            'security/forgot_password.html.twig',
            [
                'form' => $requestPasswordForm->createView(),
            ]
        );
    }
}
