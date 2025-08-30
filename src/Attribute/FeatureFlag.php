<?php

declare(strict_types=1);

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class FeatureFlag
{
    public function __construct(
        public string $flag,
    ) {
    }
}
