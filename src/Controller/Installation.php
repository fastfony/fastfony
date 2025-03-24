<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\FeaturesFormType;
use App\Form\InstallationFormType;
use App\Handler\FeatureFlag;
use App\HealthCheck\All;
use App\Installation\Step1;
use App\Installation\Step2;
use App\Installation\Step3;
use App\Repository\User\UserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * After installation proceed, you can remove this controller, the class EventListener/InstallationCheck.php, the
 * directory Installation with its files and templates in templates/installation.
 */
class Installation extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private All $allHealthChecks,
        private Step1 $step1,
        private Step2 $step2,
        private Step3 $step3,
    ) {
        if ($this->allHealthChecks->hasPreviouslyErrors()) {
            $this->allHealthChecks->checks();
        }
    }

    /** @phpstan-ignore symfony.requireInvokableController */
    #[Route('/installation', name: 'installation')]
    public function step1(): Response
    {
        // We always check if the installation is already done
        try {
            if (0 < $this->userRepository->countEnabled()) {
                return $this->redirectToRoute('homepage');
            }
        } catch (ConnectionException|TableNotFoundException $e) {
            // If the database is not created, a "normal" exception is thrown, we catch and continue
        }

        if ($this->allHealthChecks->hasPreviouslyErrors() || !$this->step1->do()) {
            $this->addFlash('error', 'installation.error');
        }

        return $this->render(
            'installation/step1.html.twig',
            [
                'form' => $this->createForm(
                    FeaturesFormType::class,
                    ['features' => FeatureFlag::FEATURES],
                ),
            ],
        );
    }

    /** @phpstan-ignore symfony.requireInvokableController */
    #[Route('/installation/2', name: 'installation_step_2', methods: ['POST'])]
    public function step2(Request $request): Response
    {
        $form = $this->createForm(FeaturesFormType::class);
        if (!$this->step2->do($form, $request)) {
            $this->addFlash('error', 'installation.error');

            return $this->render(
                'installation/step1.html.twig',
                [
                    'form' => $form,
                ],
            );
        }

        // If step 2 is OK, we display the form of step 3
        return $this->render(
            'installation/step2.html.twig',
            [
                'form' => $this->createForm(InstallationFormType::class),
            ]
        );
    }

    /** @phpstan-ignore symfony.requireInvokableController */
    #[Route('/installation/3', name: 'installation_step_3', methods: ['POST'])]
    public function step3(Request $request): Response
    {
        $form = $this->createForm(InstallationFormType::class);
        if ($this->step3->do($form, $request)) {
            return $this->render('installation/step3.html.twig');
        }

        $this->addFlash('error', 'installation.error');

        return $this->render(
            'installation/step2.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
