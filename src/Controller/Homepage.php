<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

// Le slug peut être vide pour la homepage
#[Route('/', name: 'homepage', methods: ['GET'])]
#[Template('pages/show.html.twig')]
class Homepage extends AbstractController
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
    }

    public function __invoke(
        Request $request,
    ): array {
        return [
            'page' => $this->getHomepage(),
        ];
    }

    private function getHomepage(): ?Page
    {
        try {
            return $this->pageRepository
                ->findOneBy(['homepage' => true]);
        } catch (NonUniqueResultException $exception) {
            throw $this->createNotFoundException(
                'You have multiple homepages configured.',
            );
        }
    }
}
