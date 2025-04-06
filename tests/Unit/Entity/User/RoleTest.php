<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Role;
use App\Tests\Unit\Entity\ToStringTestTrait;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    use ToStringTestTrait;

    public function testToString(): void
    {
        $this->toStringTest(
            Role::class,
            'description',
        );
    }
}
