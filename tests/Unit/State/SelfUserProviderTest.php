<?php

declare(strict_types=1);

namespace App\Tests\Unit\State;

use ApiPlatform\Metadata\Operation;
use App\Entity\User\User;
use App\State\SelfUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class SelfUserProviderTest extends TestCase
{
    public function testProvide(): void
    {
        $user = $this->createMock(User::class);

        $security = $this->createMock(Security::class);

        $security->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $selfUserProvider = new SelfUserProvider($security);
        $actual = $selfUserProvider->provide(
            $this->createMock(Operation::class),
        );

        $this->assertSame($user, $actual);
    }
}
