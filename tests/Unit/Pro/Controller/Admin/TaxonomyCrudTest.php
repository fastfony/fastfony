<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Controller\Admin;

use App\Pro\Controller\Admin\TaxonomyCrud;
use App\Pro\Entity\Taxonomy;
use PHPUnit\Framework\TestCase;

class TaxonomyCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = TaxonomyCrud::getEntityFqcn();
        $this->assertEquals(Taxonomy::class, $entityFqcn);
    }
}
