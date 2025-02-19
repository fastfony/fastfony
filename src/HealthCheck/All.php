<?php

declare(strict_types=1);

namespace App\HealthCheck;

use Symfony\Component\HttpFoundation\RequestStack;

class All
{
    public const SENSORS = [
        'database',
        'mailer',
    ];

    public function __construct(
        private RequestStack $requestStack,
        private Database $database,
        private Mailer $mailer,
    ) {
    }

    public function checks(): array
    {
        $checks = [];
        foreach (self::SENSORS as $sensor) {
            $checks[$sensor] = $this->$sensor->check();
        }

        $this->requestStack->getCurrentRequest()->getSession()->set('installation_checks', $checks);

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
