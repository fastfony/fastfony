<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\Admin;

use App\Controller\Admin\PriceCrud;
use App\Entity\Product\Price;
use PHPUnit\Framework\TestCase;

class PriceCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = PriceCrud::getEntityFqcn();
        $this->assertEquals(Price::class, $entityFqcn);
    }
}
