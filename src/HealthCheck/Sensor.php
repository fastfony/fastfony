<?php

declare(strict_types=1);

namespace App\HealthCheck;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.healthcheck.sensor')]
interface Sensor
{
    public function check(): bool;
}
