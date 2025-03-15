<?php

declare(strict_types=1);

namespace App\HealthCheck;

class Internet
{
    public function check(): bool
    {
        $connection = @fsockopen('google.com', 443);
        if ($connection) {
            fclose($connection);

            return true;
        }

        $connection = @fsockopen('fastfony.com', 443);
        if ($connection) {
            fclose($connection);

            return true;
        }

        return false;
    }
}
