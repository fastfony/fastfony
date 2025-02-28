<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidJsonValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (null === json_decode($value, true) && \JSON_ERROR_NONE !== json_last_error()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
