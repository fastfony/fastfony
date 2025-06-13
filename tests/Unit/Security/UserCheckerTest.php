<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Entity\User\User;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerTest extends TestCase
{
    private UserChecker $userChecker;

    protected function setUp(): void
    {
        $this->userChecker = new UserChecker();
    }

    public function testCheckPreAuthWithNonUserInterface(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $this->userChecker->checkPreAuth($userMock);
        $this->addToAssertionCount(1);
    }

    public function testCheckPreAuthWithEnabledUser(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getPassword')->willReturn('hashed_password');
        $user->method('isEnabled')->willReturn(true);

        $this->userChecker->checkPreAuth($user);
        $this->addToAssertionCount(1);
    }

    public function testCheckPreAuthWithDisabledUser(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getPassword')->willReturn('hashed_password');
        $user->method('isEnabled')->willReturn(false);

        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Inactive account');

        $this->userChecker->checkPreAuth($user);
    }

    public function testCheckPreAuthWithNoPasswordUser(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getPassword')->willReturn(null);
        $user->method('isEnabled')->willReturn(false);

        $this->userChecker->checkPreAuth($user);
        $this->addToAssertionCount(1);
    }

    public function testCheckPostAuth(): void
    {
        $user = $this->createMock(User::class);

        $this->userChecker->checkPostAuth($user);
        $this->addToAssertionCount(1);
    }
}
