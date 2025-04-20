<?php

declare(strict_types=1);

namespace App\Pro\Twig;

use App\Pro\Repository\Collection\RecordCollectionRepository;
use App\Pro\Repository\Collection\RecordRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Record extends AbstractExtension
{
    public function __construct(
        private RecordCollectionRepository $recordCollectionRepository,
        private RecordRepository $recordRepository,
    ) {
    }

    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_published_records', [$this, 'getPublishedRecord']),
        ];
    }

    public function getPublishedRecord(string $collectionSlug): ?array
    {
        $recordCollection = $this->recordCollectionRepository->findOneBy(['slug' => $collectionSlug]);
        if (null === $recordCollection) {
            return null;
        }

        return $this->recordRepository->findBy([
            'collection' => $recordCollection,
            'published' => true,
        ]);
    }
}
