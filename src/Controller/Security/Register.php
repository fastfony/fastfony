<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use App\Security\LoginLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', name: 'register')]
class Register extends AbstractController
{
    public function __construct(
        private LoginLink $loginLink,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(
        Request $request,
    ): Response {
        $registerForm = $this->createForm(RegisterFormType::class);

        if ($request->isMethod('POST')) {
            $registerForm->handleRequest($request);

            if ($registerForm->isSubmitted() && $registerForm->isValid()) {
                $email = $registerForm->get('email')->getData();
                $user = $this->userRepository->findOneBy([
                    'email' => $email,
                ]);

                if (null === $user) {
                    $user = $this->userRepository->create($email);
                }

                if (!$this->loginLink->send($user)) {
                    $this->addFlash(
                        'error',
                        'flash.login_link_sent.error',
                    );
                }

                // We always redirect to the confirm message to avoid user enumeration
                return $this->render(
                    'security/register.html.twig',
                    [],
                    new Response(null, Response::HTTP_SEE_OTHER), // For Turbo drive compatibility
                );
            }
        }

        return $this->render(
            'security/register.html.twig',
            [
                'form' => $registerForm->createView(),
            ]
        );
    }
}
