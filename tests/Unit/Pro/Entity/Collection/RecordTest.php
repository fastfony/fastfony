<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Collection;

use App\Pro\Entity\Collection\Field;
use App\Pro\Entity\Collection\Record;
use App\Pro\Entity\Collection\RecordCollection;
use App\Pro\Entity\Collection\RecordFieldValue;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    private Record $record;
    private RecordFieldValue $fieldValue1;
    private RecordFieldValue $fieldValue2;
    private Field $field1;
    private Field $field2;

    protected function setUp(): void
    {
        $this->record = new Record();
        $this->record->setCollection(new RecordCollection());

        $this->field1 = new Field();
        $this->field1->setName('titre');

        $this->fieldValue1 = new RecordFieldValue();
        $this->fieldValue1->setField($this->field1);
        $this->fieldValue1->setValue('Titre du record');
        $this->fieldValue1->setRecord($this->record);

        $this->field2 = new Field();
        $this->field2->setName('description');

        $this->fieldValue2 = new RecordFieldValue();
        $this->fieldValue2->setField($this->field2);
        $this->fieldValue2->setValue('Description du record');
        $this->fieldValue2->setRecord($this->record);

        $this->record->addField($this->fieldValue1);
        $this->record->addField($this->fieldValue2);
    }

    public function testGetArrayFields(): void
    {
        $arrayFields = $this->record->getArrayFields();

        $this->assertArrayHasKey('titre', $arrayFields);
        $this->assertArrayHasKey('description', $arrayFields);
        $this->assertEquals('Titre du record', $arrayFields['titre']);
        $this->assertEquals('Description du record', $arrayFields['description']);
        $this->assertCount(2, $arrayFields);
    }

    public function testRemoveField(): void
    {
        $this->assertCount(2, $this->record->getFields());

        $this->record->removeField($this->fieldValue1);

        $this->assertCount(1, $this->record->getFields());
        $this->assertFalse($this->record->getFields()->contains($this->fieldValue1));
        $this->assertTrue($this->record->getFields()->contains($this->fieldValue2));

        $arrayFields = $this->record->getArrayFields();
        $this->assertArrayNotHasKey('titre', $arrayFields);
        $this->assertArrayHasKey('description', $arrayFields);
    }

    public function testRemoveFieldSetsRecordToNull(): void
    {
        $this->assertSame($this->record, $this->fieldValue1->getRecord());
        $this->record->removeField($this->fieldValue1);
        $this->assertNull($this->fieldValue1->getRecord());
    }
}
