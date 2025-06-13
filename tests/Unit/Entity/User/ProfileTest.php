<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Profile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProfileTest extends TestCase
{
    private Profile $profile;

    protected function setUp(): void
    {
        $this->profile = new Profile();
    }

    public function testGetFullName(): void
    {
        $this->profile->setFirstName('Jean');
        $this->profile->setLastName('Dupont');

        $fullName = $this->profile->getFullName();

        $this->assertEquals('Jean Dupont', $fullName);
    }

    public function testSetPhotoFile(): void
    {
        // Create a temporary file for testing
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        $file = new File($tempFile);

        $result = $this->profile->setPhotoFile($file);

        $this->assertSame($this->profile, $result);
        $this->assertSame($file, $this->profile->getPhotoFile());

        // Check that updatedAt was updated when setting a file
        $reflection = new \ReflectionProperty($this->profile, 'updatedAt');
        $updatedAt = $reflection->getValue($this->profile);

        $this->assertInstanceOf(\DateTime::class, $updatedAt);
        $this->assertEqualsWithDelta(time(), $updatedAt->getTimestamp(), 5);

        // Cleanup
        @unlink($tempFile);
    }

    public function testSetPhotoFileWithNull(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        $file = new File($tempFile);
        $this->profile->setPhotoFile($file);

        $result = $this->profile->setPhotoFile(null);

        $this->assertSame($this->profile, $result);
        $this->assertNull($this->profile->getPhotoFile());

        @unlink($tempFile);
    }
}
