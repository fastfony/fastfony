<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener\Security;

use App\Entity\User\User;
use App\EventListener\Security\LoginSuccess;
use App\Repository\User\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessTest extends TestCase
{
    public function testLoginSuccessEventListener(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $loginSuccessEventListener = new LoginSuccess(
            $userRepository
        );

        $event = $this->createMock(LoginSuccessEvent::class);
        $event->method('getUser')
            ->willReturn(new User());

        // The repository for save new boolean enabled value must be called
        $userRepository->expects($this->once())
            ->method('save');

        $loginSuccessEventListener->__invoke($event);
    }
}
