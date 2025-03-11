<?php

declare(strict_types=1);

namespace App\Tests\Unit\HealthCheck;

use App\HealthCheck\Mailer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    private MailerInterface|MockObject $mailerInterface;
    private RequestStack|MockObject $requestStack;
    private Mailer $mailer;

    protected function setUp(): void
    {
        $this->mailerInterface = $this->createMock(MailerInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->mailer = new Mailer(
            $this->mailerInterface,
            $this->requestStack,
        );
    }

    public function testCheckNoRequest(): void
    {
        $this->requestStack->method('getCurrentRequest')
            ->willReturn(null);

        $this->assertFalse($this->mailer->check());
    }

    public function testCheckConfigMailerKo(): void
    {
        $request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')
            ->willReturn($request);

        $request->method('getHost')
            ->willReturn('localhost.tld');

        $this->mailerInterface->method('send')
            ->willThrowException(new TransportException());

        $this->assertFalse($this->mailer->check());
    }
}
