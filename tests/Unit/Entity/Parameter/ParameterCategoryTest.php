<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Parameter;

use App\Entity\Parameter\Parameter;
use App\Entity\Parameter\ParameterCategory;
use App\Tests\Unit\Entity\ToStringTestTrait;
use PHPUnit\Framework\TestCase;

class ParameterCategoryTest extends TestCase
{
    use ToStringTestTrait;

    public function testToString(): void
    {
        $this->toStringTest(
            ParameterCategory::class,
            'name',
        );
    }

    public function testRemoveParameter(): void
    {
        $parameterCategory = new ParameterCategory();
        $parameter = (new Parameter())
            ->setCategory($parameterCategory)
        ;

        $parameterCategory->addParameter($parameter);
        $parameterCategory->removeParameter($parameter);
        $this->assertEquals(0, $parameterCategory->getParameters()->count());
    }
}
