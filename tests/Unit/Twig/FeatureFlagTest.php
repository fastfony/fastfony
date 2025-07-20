<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig;

use App\Handler\FeatureFlag as FeatureFlagHandler;
use App\Twig\FeatureFlag;
use PHPUnit\Framework\TestCase;

class FeatureFlagTest extends TestCase
{
    public function testFeatureIsDisabledByDefault()
    {
        $handler = $this->createMock(FeatureFlagHandler::class);
        $handler->method('isEnabled')->with('nouvelle_fonctionnalite')->willReturn(false);

        $featureFlag = new FeatureFlag($handler);
        $this->assertFalse($featureFlag->featureEnabled('nouvelle_fonctionnalite'));
    }

    public function testEnableFeature()
    {
        $handler = $this->createMock(FeatureFlagHandler::class);
        $handler->method('isEnabled')->with('nouvelle_fonctionnalite')->willReturn(true);

        $featureFlag = new FeatureFlag($handler);
        $this->assertTrue($featureFlag->featureEnabled('nouvelle_fonctionnalite'));
    }

    public function testDisableFeature()
    {
        $handler = $this->createMock(FeatureFlagHandler::class);
        $handler->method('isEnabled')->with('nouvelle_fonctionnalite')->willReturn(false);

        $featureFlag = new FeatureFlag($handler);
        $this->assertFalse($featureFlag->featureEnabled('nouvelle_fonctionnalite'));
    }
}
