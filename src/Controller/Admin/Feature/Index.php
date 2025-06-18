<?php

declare(strict_types=1);

namespace App\Controller\Admin\Feature;

use App\Form\FeaturesFormType;
use App\Handler\FeatureFlag;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    #[Route('/admin/features', name: 'admin_features')]
    public function __invoke(
        Request $request,
    ): Response {
        $featureFlagForm = $this->createForm(
            FeaturesFormType::class,
            ['features' => $this->featureFlag->getEnabled()]
        );

        if ($request->isMethod('POST')) {
            $featureFlagForm->handleRequest($request);

            if ($featureFlagForm->isSubmitted() && $featureFlagForm->isValid()) {
                try {
                    $this->featureFlag->save($featureFlagForm->getData()['features']);
                } catch (CannotWriteFileException $e) {
                    $this->addFlash('danger', 'flash.features.update.error');

                    return $this->redirect($this->adminUrlGenerator->generateUrl());
                }

                $this->addFlash('success', 'flash.features.update.success');

                // We redirect after the cache:clear in save method
                return $this->redirect($this->adminUrlGenerator->generateUrl());
            }
        }

        return $this->render(
            'admin/features/index.html.twig',
            [
                'feature_flag_form' => $featureFlagForm,
            ]
        );
    }
}
