<?php

declare(strict_types=1);

namespace App\Tests\Unit\FeatureFlag;

use App\Handler\FeatureFlag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureFlagTest extends TestCase
{
    public function testSave(): void
    {
        $kernelInterface = $this->createMock(KernelInterface::class);
        $featureFlag = new FeatureFlag(
            $kernelInterface,
            [
                'feature1' => true,
                'feature2' => false,
            ],
        );

        $kernelInterface->expects($this->once())
            ->method('getEnvironment')
            ->willReturn('prod');

        $kernelInterface->expects($this->once())
            ->method('getProjectDir')
            ->willReturn(__DIR__.'/../../');

        $featureFlag->save(['feature1']);
    }
}
