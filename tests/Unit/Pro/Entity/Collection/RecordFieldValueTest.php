<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Collection;

use App\Pro\Entity\Collection\RecordFieldValue;
use PHPUnit\Framework\TestCase;

class RecordFieldValueTest extends TestCase
{
    private RecordFieldValue $recordFieldValue;

    protected function setUp(): void
    {
        $this->recordFieldValue = new RecordFieldValue();
    }

    public function testGetId(): void
    {
        $this->assertNull($this->recordFieldValue->getId());

        $reflection = new \ReflectionClass($this->recordFieldValue);
        $property = $reflection->getProperty('id');
        $property->setValue($this->recordFieldValue, 123);

        $this->assertSame(123, $this->recordFieldValue->getId());
    }
}
