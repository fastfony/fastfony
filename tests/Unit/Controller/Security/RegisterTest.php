<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\Security;

use App\Controller\Security\Register;
use App\Handler\FeatureFlag;
use App\Repository\User\UserRepository;
use App\Security\LoginLink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegisterTest extends TestCase
{
    public function testRegisterDisabled(): void
    {
        $featureFlag = $this->createMock(FeatureFlag::class);
        $registerController = new Register(
            $this->createMock(LoginLink::class),
            $this->createMock(UserRepository::class),
            $featureFlag
        );

        $featureFlag->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $this->expectException(NotFoundHttpException::class);
        $registerController->__invoke($this->createMock(Request::class));
    }
}
