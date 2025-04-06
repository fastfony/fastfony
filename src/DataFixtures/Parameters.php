<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Parameter\Parameter;
use App\Entity\Parameter\ParameterCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Parameters extends Fixture implements FixtureGroupInterface
{
    private const FASTFONY_PARAMETER_CATEGORY = 'Fastfony';
    private const APP_PARAMETER_CATEGORY = 'App';
    private const EMAIL_PARAMETER_CATEGORY = 'Email';
    private const REGISTER_PARAMETER_CATEGORY = 'Registration';
    private const PARAMETER_CATEGORIES = [
        self::FASTFONY_PARAMETER_CATEGORY,
        self::APP_PARAMETER_CATEGORY,
        self::EMAIL_PARAMETER_CATEGORY,
        self::REGISTER_PARAMETER_CATEGORY,
    ];

    private const PARAMETER_CATEGORY_REFERENCE_SUFFIX = '_PARAMETER_CATEGORY_REFERENCE';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createParameterCategories($manager);
        $this->createParameters($manager);
        $manager->flush();
    }

    /**
     * @return array<string>
     */
    public static function getGroups(): array
    {
        return [
            AppFixtures::GROUP_INSTALL,
            AppFixtures::GROUP_TEST,
        ];
    }

    private function createParameterCategories(ObjectManager $manager): void
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
    }

    private function createParameters(ObjectManager $manager): void
    {
        $domain = $this->requestStack->getMainRequest()?->getHost() ?? 'domain.tld';
        $parameters = [
            'FASTFONY_LICENSE_KEY' => [
                'type' => 'text',
                'label' => 'License key',
                'help' => 'Your Fastfony license key is required to use the software. Get it on <a href="https://fastfony.com" target="_blank">fastfony.com</a>',
                'category' => $this->getReference(
                    self::FASTFONY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'FASTFONY_MAINTENANCE_MODE' => [
                'type' => 'bool',
                'value' => '0',
                'label' => 'Maintenance mode',
                'help' => 'If yes, the website will be in maintenance mode and show the template maintenance.html.twig (expect for administrator users)',
                'category' => $this->getReference(
                    self::FASTFONY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'APP_NAME' => [
                'value' => 'Fastfony',
                'type' => 'text',
                'label' => 'Name',
                'category' => $this->getReference(
                    self::APP_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'APP_ICON_FILEPATH' => [
                'value' => '/images/Fastfony-icon.png',
                'type' => 'text',
                'label' => 'Icon filepath',
                'category' => $this->getReference(
                    self::APP_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'MAILER_SENDER' => [
                'value' => 'noreply@'.$domain,
                'type' => 'email',
                'label' => 'Sender email address',
                'help' => 'This e-mail must be authorize by server configure on MAILER_DSN in .env.local',
                'category' => $this->getReference(
                    self::EMAIL_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'REGISTRATION_BACKGROUND_IMAGE_URL' => [
                'value' => 'https://images.unsplash.com/photo-1496917756835-20cb06e75b4e',
                'type' => 'url',
                'label' => 'Background image URL',
                'help' => 'An URL with https://',
                'category' => $this->getReference(
                    self::REGISTER_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
        ];

        foreach ($parameters as $key => $values) {
            $parameter = (new Parameter())
                ->setKey($key)
                ->setType($values['type'])
                ->setLabel($values['label'])
                ->setHelp($values['help'] ?? null)
                ->setValue($values['value'] ?? null)
                ->setCategory($values['category'] ?? null)
            ;

            $manager->persist($parameter);
        }
    }
}
