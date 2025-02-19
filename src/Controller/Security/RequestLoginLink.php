<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Form\LoginFormType;
use App\Repository\UserRepository;
use App\Security\LoginLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/login', name: 'login')]
class RequestLoginLink extends AbstractController
{
    public function __construct(
        private LoginLink $loginLink,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($this->getUser()) {
            $this->addFlash('info', 'flash.already_logged_in');
        }

        $loginForm = $this->createForm(LoginFormType::class);

        if ($request->isMethod('POST')) {
            $loginForm->handleRequest($request);

            if ($loginForm->isSubmitted() && $loginForm->isValid()) {
                $email = $loginForm->get('email')->getData();
                $user = $this->userRepository->findOneBy([
                    'email' => $email,
                    'enabled' => true,
                ]);

                if (null !== $user) {
                    if (!$this->loginLink->send($user)) {
                        $this->addFlash(
                            'error',
                            'flash.login_link_sent.error',
                        );
                    }
                }

                // We always redirect to the confirm message to avoid user enumeration
                return $this->render(
                    'security/request_login_link.html.twig',
                    [],
                    new Response(null, Response::HTTP_SEE_OTHER), // For Turbo drive compatibility
                );
            }
        }

        return $this->render(
            'security/request_login_link.html.twig',
            [
                'form' => $loginForm->createView(),
            ],
        );
    }
}
