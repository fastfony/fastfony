<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditorjsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($value): string {
                        // transform the array to a json string
                        return json_encode($value);
                    },
                    function ($value): ?array {
                        // transform the json string to a php array
                        return json_decode($value, true);
                    }
                )
            );
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
