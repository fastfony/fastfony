<?php

declare(strict_types=1);

namespace App\Tests\Unit\DataCollector;

use App\DataCollector\Fastfony;
use App\Kernel;
use Fastfony\LicenseBundle\Security\LicenseChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FastfonyTest extends TestCase
{
    public function testCollect(): void
    {
        $fastfony = $this->getFastfony('test-license-key');

        $this->assertEquals('test-license-key', $fastfony->getLicenseKey());
        $this->assertFalse($fastfony->getLicenseKeyValidity());
        $this->assertEquals(Kernel::FF_VERSION, $fastfony->getFastfonyVersion());
    }

    public function testGetTemplate(): void
    {
        $this->assertEquals('data_collector/fastfony.html.twig', Fastfony::getTemplate());
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

    public function testGetLicenseKey(): void
    {
        $fastfony = $this->getFastfony('test-license-key');

        $this->assertEquals('test-license-key', $fastfony->getLicenseKey());
    }

    public function testGetLicenseKeyValidity(): void
    {
        $fastfony = $this->getFastfony('test-license-key');

        $this->assertFalse($fastfony->getLicenseKeyValidity());
    }

    private function getFastfony(?string $licenseKey): Fastfony
    {
        $fastfony = new Fastfony(
            $licenseKey,
            $this->createMock(LicenseChecker::class),
        );

        $request = new Request();
        $response = new Response();

        $fastfony->collect($request, $response);

        return $fastfony;
    }
}
