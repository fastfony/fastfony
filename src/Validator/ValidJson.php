<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidJson extends Constraint
{
    public const INVALID_MESSAGE = 'The string contains a syntax error and it is not encodable to JSON.';
    public string $message = self::INVALID_MESSAGE;
}
