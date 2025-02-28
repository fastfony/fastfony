<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginFormType;
use App\HealthCheck\All;
use App\Installation\Step2;
use App\Installation\Step3;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * After installation proceed, you can remove this controller, the class EventListener/InstallationCheck.php, the
 * directory Installation with its files and templates in templates/installation (if here yet...).
 */
#[Route('/installation', name: 'installation')]
#[Route('/installation/{step}', name: 'installation_step', requirements: ['step' => '2|3'])]
class Installation extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private All $allHealthChecks,
        private Step2 $step2,
        private Step3 $step3,
    ) {
        if ($this->allHealthChecks->hasPreviouslyErrors()) {
            $this->allHealthChecks->checks();
        }
    }

    public function __invoke(
        Request $request,
        int $step = 1,
    ) {
        // We always check if the installation is already done
        try {
            if (0 < $this->userRepository->countEnabled()) {
                return $this->redirectToRoute('admin');
            }
        } catch (ConnectionException|TableNotFoundException $e) {
            // If the database is not created, a "normal" exception is thrown, we catch and continue
        }

        $loginForm = $this->createForm(LoginFormType::class);
        // If step 2 and all checks are passed
        if (2 === $step && !$this->allHealthChecks->hasPreviouslyErrors()) {
            $this->step2->do();
        }

        // If step 3 and form is submitted
        if (3 === $step && $request->isMethod('POST')) {
            $this->step3->do($loginForm, $request);
            if ($loginForm->isValid()) {
                return $this->render(
                    'installation/step'.$step.'.html.twig',
                    [],
                    new Response(null, Response::HTTP_SEE_OTHER), // This is for turbo
                );
            }
            --$step;
            $this->addFlash('error', 'installation.error');
        }

        return $this->render(
            'installation/step'.$step.'.html.twig',
            [
                'login_form' => $loginForm->createView(),
            ]
        );
    }
}
