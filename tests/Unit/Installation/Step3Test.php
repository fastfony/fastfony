<?php

declare(strict_types=1);

namespace App\Tests\Unit\Installation;

use App\Entity\Parameter\Parameter;
use App\Installation\Step3;
use App\Repository\Parameter\ParameterRepository;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class Step3Test extends TestCase
{
    public function testFailedDo(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $parameterRepository = $this->createMock(ParameterRepository::class);
        $step3 = new Step3(
            $userRepository,
            $this->createMock(LoginLink::class),
            $parameterRepository,
        );

        $userRepository->method('createSuperAdmin')
            ->willThrowException(new \Exception());

        $request = $this->createMock(Request::class);
        $installationForm = $this->createMock(FormInterface::class);

        $installationForm->method('getData')
            ->willReturn([
                'autoGenerateLicenseKey' => false,
                'email' => 'test@test.com',
                'licenseKey' => 'test-license-key',
            ]);

        $installationForm->method('isSubmitted')
            ->willReturn(true);

        $installationForm->method('isValid')
            ->willReturn(true);

        $parameterRepository->method('findOneBy')
            ->willReturn(new Parameter());

        $this->assertFalse($step3->do(
            $installationForm,
            $request,
        ));
    }
}
