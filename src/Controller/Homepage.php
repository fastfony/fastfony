<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Homepage extends AbstractController
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[Route('/', name: 'homepage', methods: ['GET'])]
    #[Template('pages/show.html.twig')]
    public function __invoke(
        Request $request,
    ): array|RedirectResponse {
        // If Fastfony is not yet installed, redirect to the installer
        $users = $this->userRepository->countEnabled();
        if (0 === $users) {
            return $this->redirectToRoute('installation');
        }

        $homepage = $this->getHomepage();

        return [
            'page' => $homepage,
            'collections' => $homepage->getRecordCollectionsAsArrayWithPublishedRecords(),
        ];
    }

    private function getHomepage(): ?Page
    {
        try {
            return $this->pageRepository->findHomepage();
        } catch (NonUniqueResultException $exception) {
            throw $this->createNotFoundException('You have multiple homepages configured.');
        }
    }
}
