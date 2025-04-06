<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\User\User;
use App\Factory\UserFactory;
use App\Repository\User\UserRepository;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UserFactoryTest extends TestCase
{
    private UserRepository|MockObject $userRepository;
    private UserFactory $userFactory;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userFactory = new UserFactory($this->userRepository);
    }

    public function testFromGoogleReturnsExistingUser(): void
    {
        $googleUser = $this->createMock(GoogleUser::class);
        $googleUser->method('getEmail')->willReturn('existing@example.com');

        $existingUser = new User();
        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn($existingUser)
        ;

        $user = $this->userFactory->fromGoogle($googleUser);
        $this->assertSame($existingUser, $user);
    }

    public function testFromGoogleCreatesNewUser(): void
    {
        $googleUser = $this->createMock(GoogleUser::class);
        $googleUser->method('getEmail')->willReturn('google-new@example.com');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['email' => 'google-new@example.com'])
            ->willReturn(null)
        ;

        $this->userRepository
            ->expects(self::once())
            ->method('create')
            ->willReturn((new User())->setEmail('google-new@example.com'));

        $user = $this->userFactory->fromGoogle($googleUser);
        $this->assertSame('google-new@example.com', $user->getEmail());
    }

    public function testFromGithubReturnsExistingUser(): void
    {
        $githubUser = $this->createMock(GithubResourceOwner::class);
        $githubUser->method('getEmail')->willReturn('existing@example.com');

        $existingUser = new User();
        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn($existingUser)
        ;

        $user = $this->userFactory->fromGithub($githubUser);
        $this->assertSame($existingUser, $user);
    }

    public function testFromGithubCreatesNewUser(): void
    {
        $githubUser = $this->createMock(GithubResourceOwner::class);
        $githubUser->method('getEmail')->willReturn('github-new@example.com');

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'github-new@example.com'])
            ->willReturn(null)
        ;

        $this->userRepository
            ->expects(self::once())
            ->method('create')
            ->willReturn((new User())->setEmail('github-new@example.com'));

        $user = $this->userFactory->fromGithub($githubUser);
        $this->assertSame('github-new@example.com', $user->getEmail());
    }
}
