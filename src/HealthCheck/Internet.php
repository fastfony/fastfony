<?php

declare(strict_types=1);

namespace App\HealthCheck;

class Internet implements Sensor
{
    public function __construct(
        private string $domain1 = 'google.com',
        private string $domain2 = 'fastfony.com',
    ) {
    }

    public function check(): bool
    {
        $connection = @fsockopen($this->domain1, 443);
        if ($connection) {
            fclose($connection);

            return true;
        }

        $connection = @fsockopen($this->domain2, 443);
        if ($connection) {
            fclose($connection);

            return true;
        }

        return false;
    }
}
