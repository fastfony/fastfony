<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Parameter;
use App\Entity\ParameterCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Installation extends Fixture implements FixtureGroupInterface
{
    public const GROUP_INSTALL = 'install';

    private const EMAIL_PARAMETER_CATEGORY = 'Email';
    private const COMPANY_PARAMETER_CATEGORY = 'Company';
    private const LOGIN_PARAMETER_CATEGORY = 'Login';
    private const PARAMETER_CATEGORIES = [
        self::EMAIL_PARAMETER_CATEGORY,
        self::COMPANY_PARAMETER_CATEGORY,
        self::LOGIN_PARAMETER_CATEGORY,
    ];

    private const PARAMETER_CATEGORY_REFERENCE_SUFFIX = '_PARAMETER_CATEGORY_REFERENCE';

    public function load(ObjectManager $manager): void
    {
        $this->createParameters($manager);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::GROUP_INSTALL];
    }

    private function createParameters(ObjectManager $manager): void
    {
        foreach (self::PARAMETER_CATEGORIES as $category) {
            $parameterCategory = (new ParameterCategory())
                ->setName($category);
            $manager->persist($parameterCategory);
            $this->addReference(
                $category.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                $parameterCategory
            );
        }

        $parameters = [
            'MAILER_SENDER' => [
                'value' => 'noreply@domain.tld',
                'label' => 'Sender email address',
                'help' => 'This e-mail must be authorize by server configure on MAILER_DSN in .env.local',
                'category' => $this->getReference(
                    self::EMAIL_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'COMPANY_ICON_FILEPATH' => [
                'value' => '/build/images/Fastfony-icon.svg',
                'label' => 'Icon filepath',
                'category' => $this->getReference(
                    self::COMPANY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'COMPANY_NAME' => [
                'value' => 'Fastfony',
                'label' => 'Name',
                'category' => $this->getReference(
                    self::COMPANY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'BACKGROUND_LOGIN_IMAGE_URL' => [
                'value' => 'https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80',
                'label' => 'Background Image URL',
                'help' => 'An URL with https://',
                'category' => $this->getReference(
                    self::LOGIN_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
        ];

        foreach ($parameters as $key => $values) {
            $parameter = (new Parameter())
                ->setKey($key)
                ->setValue($values['value'])
                ->setLabel($values['label'])
                ->setHelp($values['help'] ?? null)
            ;

            if (isset($values['category'])) {
                $parameter->setCategory($values['category']);
            }

            $manager->persist($parameter);
        }
    }
}
