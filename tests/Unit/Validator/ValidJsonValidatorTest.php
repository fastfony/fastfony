<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Validator\UniqueHomepage;
use App\Validator\ValidJson;
use App\Validator\ValidJsonValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidJsonValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $validJsonValidator = new ValidJsonValidator();
        $executionContextInterface = $this->createMock(ExecutionContextInterface::class);
        $validJsonValidator->initialize($executionContextInterface);
        $constraint = new ValidJson();

        // For this cases, nothing should happen
        $validJsonValidator->validate('', $constraint);
        $validJsonValidator->validate('{"id": 42}', $constraint);

        // Here, the json is invalid and the buildViolation method should be called
        $executionContextInterface->expects(self::once())
            ->method('buildViolation');
        $validJsonValidator->validate('json invalid', $constraint);

        $this->expectException(UnexpectedTypeException::class);
        $otherConstraint = new UniqueHomepage();
        $validJsonValidator->validate('{"id": 42}', $otherConstraint);
    }
}
