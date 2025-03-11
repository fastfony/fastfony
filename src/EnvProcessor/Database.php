<?php

declare(strict_types=1);

namespace App\EnvProcessor;

use App\Repository\Parameter\ParameterRepository;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class Database implements EnvVarProcessorInterface
{
    public function __construct(
        private ParameterRepository $parameterRepository,
    ) {
    }

    public function getEnv(string $prefix, string $name, \Closure $getEnv): ?string
    {
        try {
            // When database does not exist, it will throw an exception
            $config = $this->parameterRepository->findOneBy(['key' => $name]);

            if (null === $config) {
                return null;
            }

            return $config->getValue();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getProvidedTypes(): array
    {
        return [
            'database' => 'string',
        ];
    }
}
