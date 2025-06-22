<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\Admin;

use App\Controller\Admin\MarketingFeatureCrud;
use App\Entity\Product\MarketingFeature;
use PHPUnit\Framework\TestCase;

class MarketingFeatureCrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $entityFqcn = MarketingFeatureCrud::getEntityFqcn();
        $this->assertEquals(MarketingFeature::class, $entityFqcn);
    }
}
