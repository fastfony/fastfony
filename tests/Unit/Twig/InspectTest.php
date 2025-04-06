<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig;

use App\Twig\Inspect;
use PHPUnit\Framework\TestCase;

class InspectTest extends TestCase
{
    public function testInspect(): void
    {
        $inspect = new Inspect();
        $this->assertSame('1', $inspect->inspect(1));
    }

    public function testGetFilters(): void
    {
        $inspect = new Inspect();
        $this->assertCount(1, $inspect->getFilters());
    }
}
