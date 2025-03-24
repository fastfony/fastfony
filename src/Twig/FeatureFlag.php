<?php

declare(strict_types=1);

namespace App\Twig;

use App\Handler\FeatureFlag as FeatureFlagHandler;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureFlag extends AbstractExtension
{
    public function __construct(
        private FeatureFlagHandler $featureFlagHandler,
    ) {
    }

    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('feature_enabled', [$this->featureFlagHandler, 'isEnabled']),
        ];
    }
}
