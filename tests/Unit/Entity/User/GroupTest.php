<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Group;
use App\Tests\Unit\Entity\ToStringTestTrait;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    use ToStringTestTrait;

    public function testToString(): void
    {
        $this->toStringTest(
            Group::class,
            'name',
        );
    }
}
