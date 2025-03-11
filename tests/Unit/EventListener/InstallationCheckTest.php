<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\EventListener\InstallationCheck;
use App\HealthCheck\All;
use App\Repository\User\UserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class InstallationCheckTest extends TestCase
{
    private InstallationCheck $installationCheck;

    private RouterInterface|MockObject $router;
    private All|MockObject $healthCheck;
    private UserRepository|MockObject $userRepository;
    private RequestEvent|MockObject $requestEvent;
    private Request|MockObject $request;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->healthCheck = $this->createMock(All::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->requestEvent = $this->createMock(RequestEvent::class);
        $this->request = $this->createMock(Request::class);
        $this->requestEvent->method('getRequest')
            ->willReturn($this->request);

        $this->installationCheck = new InstallationCheck(
            $this->router,
            $this->healthCheck,
            $this->userRepository,
        );
    }

    public function testInstallationCheckWithoutDatabase(): void
    {
        $this->userRepository->expects($this->once())
            ->method('countEnabled')
            ->willThrowException($this->createMock(ConnectionException::class));

        $this->request->attributes = new InputBag();
        $this->request->attributes->set('_route', 'homepage');

        $this->redirectExcepted();

        $this->installationCheck->onKernelRequest($this->requestEvent);
    }

    public function testInstallationCheckOnExcludeRoute(): void
    {
        $this->request->attributes = new InputBag();
        $this->request->attributes->set('_route', 'installation');

        $this->installationCheck->onKernelRequest($this->requestEvent);

        // We must NOT redirect to the installation page
        $this->requestEvent->expects($this->never())
            ->method('setResponse');
    }

    public function testInstallationCheckWithAtLeastOneError(): void
    {
        $this->userRepository->expects($this->once())
            ->method('countEnabled')
            ->willReturn(0);

        $this->request->attributes = new InputBag();
        $this->request->attributes->set('_route', 'homepage');

        $this->healthCheck->expects($this->once())
            ->method('checks')
            ->willReturn([
                'database' => false,
            ]);

        $this->redirectExcepted();

        $this->installationCheck->onKernelRequest($this->requestEvent);
    }

    private function redirectExcepted(): void
    {
        $this->router->expects($this->once())
            ->method('generate')
            ->willReturn('/installation');

        // We must redirect to the installation page
        $this->requestEvent->expects($this->once())
            ->method('setResponse');
    }
}
