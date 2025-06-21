<?php

declare(strict_types=1);

namespace App\Pro\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Pro\Entity\Collection\Record;
use App\Pro\Entity\Collection\RecordCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<Record>
 */
class PublishedRecordProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @return Record|array<Record>
     */
    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Record|array {
        if ($operation instanceof Get) {
            $record = $this->entityManager->getRepository(Record::class)
                ->find($uriVariables['id']);

            if (null === $record || !$record->isPublished()) {
                throw new NotFoundHttpException('Page not found or not published');
            }

            return $record;
        }

        $collection = $this->entityManager->getRepository(RecordCollection::class)
            ->find($uriVariables['collectionId']);

        if (null === $collection) {
            throw new NotFoundHttpException('Collection not found');
        }

        return $this->entityManager->getRepository(Record::class)
            ->findBy([
                'published' => true,
                'collection' => $collection,
            ]);
    }
}
