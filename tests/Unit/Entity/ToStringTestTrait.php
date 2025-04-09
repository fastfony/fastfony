<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

trait ToStringTestTrait
{
    public function toStringTest(string $objectType, string $fieldToString): void
    {
        $methodName = 'set'.ucfirst($fieldToString);
        $object = (new $objectType())
            ->$methodName('test')
        ;

        $this->assertEquals('test', $object->__toString());
    }
}
