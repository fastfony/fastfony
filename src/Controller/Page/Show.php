<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Entity\Page\Page;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Show extends AbstractController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    #[Route(
        '/pages/{slug:page}',
        name: 'page_show',
        requirements: ['slug' => '([A-Za-z0-9]+(?:-[A-Za-z0-9]+)*)|()'],
        methods: ['GET'],
    )]
    public function __invoke(
        Page $page,
    ): Response {
        if (!$this->featureFlag->isEnabled(Features::PAGES->value)
            || (!$page->isEnabled() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'pages/show.html.twig',
            [
                'page' => $page,
                'collections' => $page->getRecordCollectionsAsArrayWithPublishedRecords(),
            ],
        );
    }
}
