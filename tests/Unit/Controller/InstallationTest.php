<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\Installation;
use App\HealthCheck\All;
use App\Installation\Step1;
use App\Installation\Step2;
use App\Installation\Step3;
use App\Repository\User\UserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\RuntimeError;

class InstallationTest extends KernelTestCase
{
    private Installation $installation;
    private UserRepository|MockObject $userRepository;
    private Step1|MockObject $step1;
    private Step2|MockObject $step2;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->step1 = $this->createMock(Step1::class);
        $this->step2 = $this->createMock(Step2::class);

        $this->installation = new Installation(
            $this->userRepository,
            $this->createMock(All::class),
            $this->step1,
            $this->step2,
            $this->createMock(Step3::class),
        );

        $this->installation->setContainer(static::getContainer());
    }

    public function testWithoutDatabase(): void
    {
        $this->userRepository->expects($this->once())
            ->method('countEnabled')
            ->willThrowException($this->createMock(ConnectionException::class));

        try {
            $this->installation->step1();
        } catch (RuntimeError|\LogicException) {
            // Logic exception is thrown because we don't have the session for FlashBag
            // Runtime error is thrown because we don't have the twig environment
        }
    }

    public function testFailedStep2(): void
    {
        $this->step2->expects($this->once())
            ->method('do')
            ->willReturn(false);

        try {
            $this->installation->step2($this->createMock(Request::class));
        } catch (\LogicException) {
            // SessionNotFoundException is thrown and rethrow by LogicException because addFlash is called, it's normal!
        }
    }
}
