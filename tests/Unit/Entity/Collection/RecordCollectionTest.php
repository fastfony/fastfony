<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Collection;

use App\Entity\Collection\Field;
use App\Entity\Collection\Record;
use App\Entity\Collection\RecordCollection;
use PHPUnit\Framework\TestCase;

class RecordCollectionTest extends TestCase
{
    private RecordCollection $recordCollection;

    protected function setUp(): void
    {
        $this->recordCollection = new RecordCollection();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->recordCollection->getId());

        $reflection = new \ReflectionClass($this->recordCollection);
        $property = $reflection->getProperty('id');
        $property->setValue($this->recordCollection, 123);

        $this->assertSame(123, $this->recordCollection->getId());
    }

    public function testGetNameAndSetName(): void
    {
        $this->assertNull($this->recordCollection->getName());

        $result = $this->recordCollection->setName('Collection de test');

        $this->assertSame($this->recordCollection, $result);
        $this->assertSame('Collection de test', $this->recordCollection->getName());
    }

    public function testSetSlug(): void
    {
        $this->assertNull($this->recordCollection->getSlug());

        $result = $this->recordCollection->setSlug('collection-de-test');

        $this->assertSame($this->recordCollection, $result);
        $this->assertSame('collection-de-test', $this->recordCollection->getSlug());
    }

    public function testRemoveRecord(): void
    {
        $record = $this->createMock(Record::class);

        $record->expects($this->once())
            ->method('getCollection')
            ->willReturn($this->recordCollection);

        $record->expects($this->exactly(2))
            ->method('setCollection');

        $this->recordCollection->addRecord($record);

        $this->assertCount(1, $this->recordCollection->getRecords());

        $result = $this->recordCollection->removeRecord($record);

        $this->assertSame($this->recordCollection, $result);

        $this->assertCount(0, $this->recordCollection->getRecords());
    }

    public function testRemoveField(): void
    {
        $field = $this->createMock(Field::class);

        $field->expects($this->once())
            ->method('getRecordCollection')
            ->willReturn($this->recordCollection);

        $field->expects($this->exactly(2))
            ->method('setRecordCollection');

        $this->recordCollection->addField($field);

        $this->assertCount(1, $this->recordCollection->getFields());

        $result = $this->recordCollection->removeField($field);

        $this->assertSame($this->recordCollection, $result);
        $this->assertCount(0, $this->recordCollection->getFields());
    }
}
