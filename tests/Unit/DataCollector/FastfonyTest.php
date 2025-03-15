<?php

declare(strict_types=1);

namespace App\Tests\Unit\DataCollector;

use App\DataCollector\Fastfony;
use App\Kernel;
use Fastfony\LicenceBundle\Security\LicenceChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FastfonyTest extends TestCase
{
    public function testCollect(): void
    {
        $fastfony = $this->getFastfony('test-licence-key');

        $this->assertEquals('test-licence-key', $fastfony->getLicenceKey());
        $this->assertFalse($fastfony->getLicenceKeyValidity());
        $this->assertEquals(Kernel::FF_VERSION, $fastfony->getFastfonyVersion());
    }

    public function testGetFastfonyState(): void
    {
        $fastfony = $this->getFastfony(null);

        $this->assertContains($fastfony->getFastfonyState(), ['eol', 'eom', 'dev', 'stable']);
    }

    public function testGetFastfonyVersion(): void
    {
        $fastfony = $this->getFastfony(null);

        $this->assertEquals(Kernel::FF_VERSION, $fastfony->getFastfonyVersion());
    }

    public function testGetLicenceKey(): void
    {
        $fastfony = $this->getFastfony('test-licence-key');

        $this->assertEquals('test-licence-key', $fastfony->getLicenceKey());
    }

    public function testGetLicenceKeyValidity(): void
    {
        $fastfony = $this->getFastfony('test-licence-key');

        $this->assertFalse($fastfony->getLicenceKeyValidity());
    }

    private function getFastfony(?string $licenceKey): Fastfony
    {
        $fastfony = new Fastfony(
            $licenceKey,
            $this->createMock(LicenceChecker::class),
        );

        $request = new Request();
        $response = new Response();

        $fastfony->collect($request, $response);

        return $fastfony;
    }
}
