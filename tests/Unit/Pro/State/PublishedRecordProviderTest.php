<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Pro\Entity\Collection\Record;
use App\Pro\Entity\Collection\RecordCollection;
use App\Pro\Repository\Collection\RecordCollectionRepository;
use App\Pro\Repository\Collection\RecordRepository;
use App\Pro\State\PublishedRecordProvider;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublishedRecordProviderTest extends TestCase
{
    private MockObject|EntityManagerInterface $entityManager;
    private MockObject|RecordRepository $recordRepository;
    private MockObject|RecordCollectionRepository $collectionRepository;
    private PublishedRecordProvider $provider;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->recordRepository = $this->createMock(RecordRepository::class);
        $this->collectionRepository = $this->createMock(RecordCollectionRepository::class);

        $this->entityManager->method('getRepository')
            ->willReturnCallback(function ($class) {
                if (Record::class === $class) {
                    return $this->recordRepository;
                }
                if (RecordCollection::class === $class) {
                    return $this->collectionRepository;
                }

                return null;
            });

        $this->provider = new PublishedRecordProvider($this->entityManager);
    }

    public function testProvideWithGetOperationAndExistingPublishedRecord(): void
    {
        $operation = new Get();
        $uriVariables = ['id' => 1];
        $context = [];

        $record = $this->createMock(Record::class);
        $record->method('isPublished')->willReturn(true);

        $this->recordRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($record);

        $result = $this->provider->provide($operation, $uriVariables, $context);

        $this->assertSame($record, $result);
    }

    public function testProvideWithGetOperationAndExistingUnpublishedRecord(): void
    {
        $operation = new Get();
        $uriVariables = ['id' => 1];
        $context = [];

        $record = $this->createMock(Record::class);
        $record->method('isPublished')->willReturn(false);

        $this->recordRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($record);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Page not found or not published');

        $this->provider->provide($operation, $uriVariables, $context);
    }

    public function testProvideWithGetOperationAndNonExistingRecord(): void
    {
        $operation = new Get();
        $uriVariables = ['id' => 1];
        $context = [];

        $this->recordRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Page not found or not published');

        $this->provider->provide($operation, $uriVariables, $context);
    }

    public function testProvideWithGetCollectionOperationAndExistingCollection(): void
    {
        $operation = new GetCollection();
        $uriVariables = ['collectionId' => 1];
        $context = [];

        $collection = $this->createMock(RecordCollection::class);
        $records = [
            $this->createMock(Record::class),
            $this->createMock(Record::class),
        ];

        $this->collectionRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($collection);

        $this->recordRepository->expects($this->once())
            ->method('findBy')
            ->with([
                'published' => true,
                'collection' => $collection,
            ])
            ->willReturn($records);

        $result = $this->provider->provide($operation, $uriVariables, $context);

        $this->assertSame($records, $result);
    }

    public function testProvideWithGetCollectionOperationAndNonExistingCollection(): void
    {
        $operation = new GetCollection();
        $uriVariables = ['collectionId' => 1];
        $context = [];

        $this->collectionRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Collection not found');

        $this->provider->provide($operation, $uriVariables, $context);
    }
}
