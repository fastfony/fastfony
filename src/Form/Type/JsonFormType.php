<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Validator\ValidJson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonFormType extends AbstractType
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    static function ($value): string {
                        if (null === $value) {
                            return '';
                        }

                        // transform the array to a json string
                        return json_encode($value, \JSON_PRETTY_PRINT);
                    },
                    function ($value): ?array {
                        if (null === $value) {
                            return null;
                        }

                        // Validate the JSON string
                        $violations = $this->validator->validate($value, new ValidJson());

                        if (\count($violations) > 0) {
                            throw new TransformationFailedException();
                        }

                        // transform the json string to a php array
                        return json_decode($value, true);
                    }
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'invalid_message', ValidJson::INVALID_MESSAGE,
        ]);
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
