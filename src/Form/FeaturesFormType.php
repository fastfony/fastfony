<?php

declare(strict_types=1);

namespace App\Form;

use App\Handler\FeatureFlag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @extends AbstractType<array>
 */
class FeaturesFormType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    /** @phpstan-ignore missingType.iterableValue */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder->add(
            'features',
            ChoiceType::class,
            [
                'choices' => array_combine(FeatureFlag::FEATURES, FeatureFlag::FEATURES),
                'multiple' => true,
                'expanded' => true,
            ]
        );
    }
}
