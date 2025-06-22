<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User\RequestPassword;
use App\Security\UserPassword;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ResetPassword extends AbstractController
{
    public function __construct(
        private readonly UserPassword $userPassword,
        private readonly Security $security,
    ) {
    }

    #[Route('/reset-password/{token}', name: 'reset_password')]
    public function __invoke(
        #[MapEntity(mapping: ['token' => 'token'])]
        RequestPassword $requestPassword,
        Request $request,
    ): Response {
        if ($requestPassword->getExpireAt() < new \DateTime()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()
            ->add(
                'password',
                PasswordType::class,
                [
                    'constraints' => [
                        new PasswordStrength(),
                    ],
                ]
            )
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->userPassword->change($requestPassword, $form->getData()['password']);

                // We connect the user and redirect to the homepage
                $this->security->login(
                    $requestPassword->getUser(),
                    'security.authenticator.remember_me.main',
                    'main',
                    [(new RememberMeBadge())->enable()],
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render(
            'security/reset_password.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
