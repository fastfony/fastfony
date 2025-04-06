<?php

declare(strict_types=1);

namespace App\Tests\Unit\Installation;

use App\Installation\Step1;
use App\Repository\Parameter\ParameterRepository;
use PHPUnit\Framework\TestCase;

class Step1Test extends TestCase
{
    public function testFailedCheckIfParamExist(): void
    {
        $parameterRepository = $this->createMock(ParameterRepository::class);
        $step1 = new Step1($parameterRepository);

        $parameterRepository->method('findOneBy')
            ->willReturn(null);

        $this->assertFalse($step1->checkIfParamExist());
    }
}
