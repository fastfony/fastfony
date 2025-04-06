<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Entity\User\User;
use App\Security\LoginLink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginLinkTest extends TestCase
{
    public function testSend(): void
    {
        $notifier = $this->createMock(NotifierInterface::class);
        $cacheItemPool = $this->createMock(FilesystemAdapter::class);

        $loginLink = new LoginLink(
            $notifier,
            $this->createMock(LoginLinkHandlerInterface::class),
            $cacheItemPool,
            $this->createMock(TranslatorInterface::class),
            'Fastfony'
        );

        $cacheItemPool->expects(self::once())
            ->method('prune');
        $cacheItemPool->expects(self::once())
            ->method('getItem')
            ->willReturn(new CacheItem());

        $notifier->method('send')
            ->willThrowException($this->createMock(HandlerFailedException::class));

        $user = (new User())
            ->setEmail('user@fastfony.com');

        $this->assertFalse($loginLink->send($user));
    }
}
