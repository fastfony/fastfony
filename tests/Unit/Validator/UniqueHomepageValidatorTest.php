<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\Page\Page;
use App\Entity\Parameter\Parameter;
use App\Repository\Page\PageRepository;
use App\Validator\UniqueHomepage;
use App\Validator\UniqueHomepageValidator;
use App\Validator\ValidJson;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueHomepageValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $pageRepository = $this->createMock(PageRepository::class);
        $uniqueHomepageValidator = new UniqueHomepageValidator($pageRepository);
        $constraint = new UniqueHomepage();

        $pageRepository->method('findOneBy')
            ->willReturn((new Page())->setHomepage(true));

        $value = (new Page())
            ->setHomepage(true);
        $value->id = 42;

        $executionContextInterface = $this->createMock(ExecutionContextInterface::class);
        $uniqueHomepageValidator->initialize($executionContextInterface);

        $executionContextInterface
            ->expects(self::once())
            ->method('buildViolation');

        $uniqueHomepageValidator->validate($value, $constraint);

        $this->expectException(UnexpectedValueException::class);
        $uniqueHomepageValidator->validate(new Parameter(), $constraint);
    }

    public function testFailedValidate(): void
    {
        $pageRepository = $this->createMock(PageRepository::class);
        $uniqueHomepageValidator = new UniqueHomepageValidator($pageRepository);

        $this->expectException(UnexpectedTypeException::class);
        $otherConstraint = new ValidJson();
        $uniqueHomepageValidator->validate(new Page(), $otherConstraint);
    }
}
