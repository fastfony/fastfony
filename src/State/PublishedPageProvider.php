<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublishedPageProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Page|array {
        if ($operation instanceof Get) {
            $page = $this->entityManager->getRepository(Page::class)
                ->find($uriVariables['id']);

            if (null === $page || !$page->isPublished()) {
                throw new NotFoundHttpException('Page not found or not published');
            }

            return $page;
        }

        return $this->entityManager->getRepository(Page::class)
            ->findBy(['published' => true]);
    }
}
