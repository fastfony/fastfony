<?php

declare(strict_types=1);

namespace App\Tests\Unit\HealthCheck;

use App\HealthCheck\Internet;
use PHPUnit\Framework\TestCase;

class InternetTest extends TestCase
{
    public function testCheck(): void
    {
        $internet = new Internet();
        $this->assertTrue($internet->check());

        $internet = new Internet('domain1.tld');
        $this->assertTrue($internet->check());
    }

    public function testFailedCheck(): void
    {
        $internet = new Internet('domain1.tld', 'domain2.tld');
        $this->assertFalse($internet->check());
    }
}
