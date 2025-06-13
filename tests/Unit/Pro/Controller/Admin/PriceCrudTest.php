<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Controller\Admin;

use App\Pro\Controller\Admin\PriceCrud;
use App\Pro\Entity\Product\Price;
use PHPUnit\Framework\TestCase;

class PriceCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = PriceCrud::getEntityFqcn();
        $this->assertEquals(Price::class, $entityFqcn);
    }
}
