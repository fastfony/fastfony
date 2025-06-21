<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Form\ContactRequestFormType;
use App\Repository\ContactRequestRepository;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Contact extends AbstractController
{
    public function __construct(
        private readonly ContactRequestRepository $contactRequestRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route('/contact')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ContactRequestFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactRequest = $form->getData();
            $user = $this->userRepository->findOneBy(['email' => $contactRequest->getEmail()]);
            if (null === $user) {
                $user = $this->userRepository->create($contactRequest->getEmail());
            }

            $contactRequest->setUser($user);

            // An event listener postPersist will send the email
            $this->contactRequestRepository->save($form->getData());

            $this->addFlash('success', 'flash.contact.success');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'pages/contact.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
