<?php

declare(strict_types=1);

namespace App\Tests\Pro\Entity\Collection;

use App\Pro\Entity\Collection\Field;
use App\Pro\Entity\Collection\RecordCollection;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    private Field $field;

    protected function setUp(): void
    {
        $this->field = new Field();
    }

    public function testGetIdReturnsNullByDefault(): void
    {
        $this->assertNull($this->field->getId());
    }

    public function testGetTypeReturnsNullByDefault(): void
    {
        $this->assertNull($this->field->getType());
    }

    public function testSetTypeAndGetType(): void
    {
        $type = 'text';

        $result = $this->field->setType($type);

        $this->assertSame($this->field, $result);
        $this->assertSame($type, $this->field->getType());
    }

    public function testIsHiddenReturnsFalseByDefault(): void
    {
        $this->assertFalse($this->field->isHidden());
    }

    public function testSetHiddenAndIsHidden(): void
    {
        $result = $this->field->setHidden(true);

        $this->assertSame($this->field, $result);
        $this->assertTrue($this->field->isHidden());
    }

    public function testIsNonemptyReturnsFalseByDefault(): void
    {
        $this->assertFalse($this->field->isNonempty());
    }

    public function testSetNonemptyAndIsNonempty(): void
    {
        $result = $this->field->setNonempty(true);

        $this->assertSame($this->field, $result);
        $this->assertTrue($this->field->isNonempty());
    }

    public function testIsPresentableReturnsTrueByDefault(): void
    {
        $this->assertTrue($this->field->isPresentable());
    }

    public function testSetPresentableAndIsPresentable(): void
    {
        $result = $this->field->setPresentable(false);

        $this->assertSame($this->field, $result);
        $this->assertFalse($this->field->isPresentable());
    }

    public function testRecordCollectionAssociation(): void
    {
        $recordCollection = $this->createMock(RecordCollection::class);

        $result = $this->field->setRecordCollection($recordCollection);

        $this->assertSame($this->field, $result);
        $this->assertSame($recordCollection, $this->field->getRecordCollection());
    }
}
