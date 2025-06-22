<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\Admin;

use App\Controller\Admin\TaxonomyCrud;
use App\Entity\Taxonomy;
use PHPUnit\Framework\TestCase;

class TaxonomyCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = TaxonomyCrud::getEntityFqcn();
        $this->assertEquals(Taxonomy::class, $entityFqcn);
    }
}
