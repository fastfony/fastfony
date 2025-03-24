<?php

declare(strict_types=1);

namespace App\Tests\Unit\Installation;

use App\Handler\FeatureFlag;
use App\Installation\Step2;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step2Test extends TestCase
{
    public function testFalseDo(): void
    {
        $request = $this->createMock(Request::class);
        $step2 = new Step2(
            $this->createMock(FeatureFlag::class),
        );

        $request->expects(self::once())
            ->method('isMethod')
            ->willReturn(false);

        $this->assertFalse($step2->do(
            $this->createMock(FormInterface::class),
            $request,
        ));
    }
}
