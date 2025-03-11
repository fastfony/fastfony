<?php

declare(strict_types=1);

namespace App\Tests\Unit\Installation;

use App\Installation\Step2;
use App\Repository\Parameter\ParameterRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class Step2Test extends TestCase
{
    public function testFailedCheckIfParamExist(): void
    {
        $parameterRepository = $this->createMock(ParameterRepository::class);
        $step2 = new Step2(
            $this->createMock(KernelInterface::class),
            $parameterRepository,
        );

        $parameterRepository->method('findOneBy')
            ->willReturn(null);

        $this->assertFalse($step2->checkIfParamExist());
    }
}
