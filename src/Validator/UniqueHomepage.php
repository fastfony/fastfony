<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueHomepage extends Constraint
{
    public string $message = 'There can only be one homepage.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
