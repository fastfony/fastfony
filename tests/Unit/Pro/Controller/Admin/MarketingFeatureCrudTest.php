<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Controller\Admin;

use App\Pro\Controller\Admin\MarketingFeatureCrud;
use App\Pro\Entity\Product\MarketingFeature;
use PHPUnit\Framework\TestCase;

class MarketingFeatureCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = MarketingFeatureCrud::getEntityFqcn();
        $this->assertEquals(MarketingFeature::class, $entityFqcn);
    }
}
