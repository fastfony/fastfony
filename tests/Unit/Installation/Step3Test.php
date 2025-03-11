<?php

declare(strict_types=1);

namespace App\Tests\Unit\Installation;

use App\Installation\Step3;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step3Test extends TestCase
{
    public function testFailedDo(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $step3 = new Step3(
            $userRepository,
            $this->createMock(LoginLink::class),
        );

        $userRepository->method('createSuperAdmin')
            ->willThrowException(new \Exception());

        $request = $this->createMock(Request::class);
        $loginForm = $this->createMock(FormInterface::class);

        $loginForm->method('isSubmitted')
            ->willReturn(true);

        $loginForm->method('isValid')
            ->willReturn(true);

        $this->assertFalse($step3->do(
            $loginForm,
            $request,
        ));
    }
}
