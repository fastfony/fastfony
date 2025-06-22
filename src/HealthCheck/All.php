<?php

declare(strict_types=1);

namespace App\HealthCheck;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\RequestStack;

class All
{
    /**
     * @param iterable<Sensor> $sensors
     */
    public function __construct(
        private RequestStack $requestStack,
        #[AutowireIterator(tag: 'app.healthcheck.sensor')]
        private iterable $sensors,
    ) {
    }

    /**
     * @return array<string, bool>
     */
    public function checks(): array
    {
        $checks = [];
        foreach ($this->sensors as $sensor) {
            $checks[$sensor::class] = $sensor->check();
        }

        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $request->getSession()->set('installation_checks', $checks);
        }

        return $checks;
    }

    public function hasPreviouslyErrors(): bool
    {
        return \in_array(
            false,
            $this->requestStack->getCurrentRequest()->getSession()->get('installation_checks', ['none' => false]),
            true
        );
    }
}
