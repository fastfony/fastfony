<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueHomepageValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Page) {
            throw new UnexpectedValueException($value, Page::class);
        }

        if ($value->isHomepage()) {
            $existingPage = $this->pageRepository->findOneBy(['homepage' => true]);

            if ($existingPage && $existingPage->getId() !== $value->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('homepage')
                    ->addViolation();
            }
        }
    }
}
