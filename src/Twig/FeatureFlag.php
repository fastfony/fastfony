<?php

declare(strict_types=1);

namespace App\Twig;

use App\Handler\FeatureFlag as FeatureFlagHandler;
use Twig\Attribute\AsTwigFunction;

class FeatureFlag
{
    public function __construct(
        private FeatureFlagHandler $featureFlagHandler,
    ) {
    }

    #[AsTwigFunction('feature_enabled')]
    public function featureEnabled(string $feature): bool
    {
        return $this->featureFlagHandler->isEnabled($feature);
    }
}
