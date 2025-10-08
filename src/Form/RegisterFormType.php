<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @extends AbstractType<array>
 */
class RegisterFormType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('agreeWithTerms', CheckboxType::class)
        ;
    }

    public function getParent(): string
    {
        return LoginFormType::class;
    }
}
