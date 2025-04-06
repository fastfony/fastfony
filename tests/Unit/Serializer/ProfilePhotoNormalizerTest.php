<?php

declare(strict_types=1);

namespace App\Tests\Unit\Serializer;

use App\Entity\User\Profile;
use App\Serializer\ProfilePhotoNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class ProfilePhotoNormalizerTest extends TestCase
{
    private ProfilePhotoNormalizer $normalizer;
    private NormalizerInterface|MockObject $decoratedNormalizer;
    private StorageInterface|MockObject $storage;

    protected function setUp(): void
    {
        $this->decoratedNormalizer = $this->createMock(NormalizerInterface::class);
        $this->storage = $this->createMock(StorageInterface::class);
        $this->normalizer = new ProfilePhotoNormalizer(
            $this->decoratedNormalizer,
            $this->storage
        );
    }

    public function testSupportsNormalization(): void
    {
        $profile = $this->createMock(Profile::class);
        $context = [];

        $this->assertTrue($this->normalizer->supportsNormalization($profile, null, $context));
        $context['PROFILE_PHOTO_NORMALIZER_ALREADY_CALLED'] = true;
        $this->assertFalse($this->normalizer->supportsNormalization($profile, null, $context));
    }

    public function testNormalize(): void
    {
        $profile = $this->createMock(Profile::class);
        $context = [];

        $this->storage->expects($this->once())
            ->method('resolveUri')
            ->with($profile, 'photoFile');
        $this->decoratedNormalizer->expects($this->once())
            ->method('normalize');

        $this->normalizer->normalize($profile, null, $context);
    }

    public function testGetSupportedTypes(): void
    {
        $supportedTypes = $this->normalizer->getSupportedTypes(null);
        $this->assertArrayHasKey(Profile::class, $supportedTypes);
        $this->assertTrue($supportedTypes[Profile::class]);
    }
}
