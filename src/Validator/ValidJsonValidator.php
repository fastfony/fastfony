<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidJsonValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidJson) {
            throw new UnexpectedTypeException($constraint, ValidJson::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (null === json_decode($value, true) && \JSON_ERROR_NONE !== json_last_error()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
