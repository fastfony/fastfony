<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\User;
use App\Notifier\AppNotification;
use App\Repository\User\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Attribute\Route;

#[AdminRoute('/app-notification', name: 'business_stats')]
class CreateAppNotification extends AbstractController
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route(
        '/admin/app-notification/create',
        name: 'admin_app_notification_create'
    )]
    #[AdminRoute('/create', name: 'create')]
    public function __invoke(
        Request $request,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $appNotification = new AppNotification(
            $user,
            '',
            ''
        );

        $form = $this->createFormBuilder($appNotification)
            ->add(
                'recipient',
                EntityType::class,
                [
                    'class' => User::class,
                    'query_builder' => fn () => $this->userRepository->createQueryBuilder('u')
                        ->join('u.appChannel', 'ac')
                        ->where('ac IS NOT NULL'),
                ],
            )
            ->add('subject', TextType::class)
            ->add('content', TextareaType::class)
            ->add(
                'importance',
                ChoiceType::class,
                [
                    'choices' => [
                        Notification::IMPORTANCE_URGENT => Notification::IMPORTANCE_URGENT,
                        Notification::IMPORTANCE_HIGH => Notification::IMPORTANCE_HIGH,
                        Notification::IMPORTANCE_MEDIUM => Notification::IMPORTANCE_MEDIUM,
                        Notification::IMPORTANCE_LOW => Notification::IMPORTANCE_LOW,
                    ],
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appNotification = $form->getData();
            $this->notifier->send($appNotification);

            $this->addFlash('success', 'Notification sent successfully!');

            return $this->redirectToRoute('admin_app_notification_create');
        }

        return $this->render(
            'admin/app_notification/create.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}
