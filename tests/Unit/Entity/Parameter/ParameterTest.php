<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Parameter;

use App\Entity\Parameter\Parameter;
use App\Entity\Parameter\ParameterCategory;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    public function testGetCategoryName(): void
    {
        $parameter = (new Parameter());

        $this->assertNull($parameter->getCategoryName());

        $parameter->setCategory(
            (new ParameterCategory())
                ->setName('category')
        );

        $this->assertSame('category', $parameter->getCategoryName());
    }
}
