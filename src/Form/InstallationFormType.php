<?php

declare(strict_types=1);

namespace App\Form;

use Fastfony\LicenseBundle\Validator\ValidLicenseKey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallationFormType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'licenseKey',
                TextareaType::class,
                [
                    'required' => false, // Will be required if autoGenerateLicenseKey is not checked
                    'constraints' => [
                        new NotBlank(),
                        new ValidLicenseKey(),
                    ],
                ],
            )
            ->add(
                'autoGenerateLicenseKey',
                CheckboxType::class,
                [
                    'required' => false,
                ],
            )
        ;
    }

    public function getParent(): string
    {
        return LoginFormType::class;
    }
}
