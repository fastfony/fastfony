<?php

declare(strict_types=1);

namespace App\Handler;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureFlag
{
    public const FEATURES = [
        Features::USERS_MANAGEMENT->value,
        Features::REGISTRATION->value,
        Features::PAGES->value,
        Features::COLLECTIONS->value,
        Features::CONTACT_REQUESTS->value,
        Features::PRODUCTS->value,
        Features::THEME_CHOOSER->value,
        Features::OAUTH2_SERVER->value,
    ];

    /**
     * @param array <string, bool> $features
     */
    public function __construct(
        private readonly KernelInterface $kernel,
        private array $features = [],
    ) {
    }

    public function isEnabled(string $feature): bool
    {
        return isset($this->features[$feature]) && true === $this->features[$feature];
    }

    /**
     * @return array <string>
     */
    public function getEnabled(): array
    {
        return array_keys($this->features);
    }

    /**
     * @param array <int, string> $enabledFeatures
     */
    public function save(array $enabledFeatures): void
    {
        $featureJson = json_encode(array_fill_keys(
            $enabledFeatures,
            true
        ));

        // Update .env.local file
        $envContent = "\nFEATURE_FLAGS='{$featureJson}'\n";
        if (file_exists('../.env.local')) {
            $envContent = file_get_contents('../.env.local');
        }

        if (preg_match('/^FEATURE_FLAGS=.*$/m', $envContent)) {
            $envContent = preg_replace(
                '/^FEATURE_FLAGS=.*$/m',
                "FEATURE_FLAGS='{$featureJson}'",
                $envContent,
            );
        }

        file_put_contents('../.env.local', $envContent);

        $dotenv = new Dotenv();
        $dotenv->loadEnv($this->kernel->getProjectDir().'/.env');
    }
}
