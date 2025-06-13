<?php

declare(strict_types=1);

namespace App\Tests\Entity\User;

use App\Entity\User\RequestPassword;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RequestPasswordTest extends TestCase
{
    private RequestPassword $requestPassword;

    protected function setUp(): void
    {
        $this->requestPassword = new RequestPassword();
    }

    public function testGetToken(): void
    {
        // The token is automatically generated in the constructor
        $token = $this->requestPassword->getToken();

        $this->assertInstanceOf(Uuid::class, $token);
    }

    public function testSetToken(): void
    {
        $newToken = Uuid::v4();
        $result = $this->requestPassword->setToken($newToken);

        $this->assertSame($newToken, $this->requestPassword->getToken());
        $this->assertSame($this->requestPassword, $result);
    }

    public function testGetExpireAt(): void
    {
        // La date d'expiration est automatiquement calculée dans le constructeur
        $expireAt = $this->requestPassword->getExpireAt();

        $this->assertInstanceOf(\DateTimeImmutable::class, $expireAt);

        // Check if the expiration date is in the future
        $now = new \DateTimeImmutable();
        $this->assertTrue($expireAt > $now);
    }

    public function testSetExpireAt(): void
    {
        $newExpireAt = new \DateTimeImmutable('+1 hour');
        $result = $this->requestPassword->setExpireAt($newExpireAt);

        $this->assertSame($newExpireAt, $this->requestPassword->getExpireAt());
        $this->assertSame($this->requestPassword, $result);
    }
}
