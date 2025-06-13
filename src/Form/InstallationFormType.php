<?php

declare(strict_types=1);

namespace App\Form;

use Fastfony\LicenseBundle\Security\LicenseChecker;
use Fastfony\LicenseBundle\Validator\ValidLicenseKey;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallationFormType extends AbstractType
{
    public function __construct(
        #[Autowire(service: 'fastfony_license.security.license_checker')]
        private readonly LicenseChecker $licenseChecker,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'mailerSender',
                EmailType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                    ],
                ],
            )
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
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (isset($data['autoGenerateLicenseKey'])
                    && true === (bool) $data['autoGenerateLicenseKey']
                    && $data['email']) {
                    $licenseKey = $this->licenseChecker->generate($data['email'], 'fastfony');
                    $event->setData(
                        array_merge(
                            $data,
                            [
                                'licenseKey' => $licenseKey
                                    ?? 'Error during generation of a new license key... Please contact support.',
                            ]
                        ),
                    );
                }
            })
        ;
    }

    public function getParent(): string
    {
        return LoginFormType::class;
    }
}
