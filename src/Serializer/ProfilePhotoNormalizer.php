<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\User\Profile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class ProfilePhotoNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'PROFILE_PHOTO_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'api_platform.jsonld.normalizer.item')]
        private readonly NormalizerInterface $normalizer,
        private readonly StorageInterface $storage,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): array|string|int|float|bool|\ArrayObject|null {
        $context[self::ALREADY_CALLED] = true;

        $data->photoUrl = $this->storage->resolveUri($data, 'photoFile');

        return $this->normalizer->normalize($data, $format, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Profile;
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            Profile::class => true,
        ];
    }
}
