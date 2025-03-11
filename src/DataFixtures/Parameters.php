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
    private const EMAIL_PARAMETER_CATEGORY = 'Email';
    private const COMPANY_PARAMETER_CATEGORY = 'Company';
    private const LOGIN_PARAMETER_CATEGORY = 'Login';
    private const PARAMETER_CATEGORIES = [
        self::EMAIL_PARAMETER_CATEGORY,
        self::COMPANY_PARAMETER_CATEGORY,
        self::LOGIN_PARAMETER_CATEGORY,
    ];

    private const PARAMETER_CATEGORY_REFERENCE_SUFFIX = '_PARAMETER_CATEGORY_REFERENCE';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
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
            'COMPANY_ICON_FILEPATH' => [
                'value' => '/images/Fastfony-icon.png',
                'type' => 'text',
                'label' => 'Icon filepath',
                'category' => $this->getReference(
                    self::COMPANY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'COMPANY_NAME' => [
                'value' => 'Fastfony',
                'type' => 'text',
                'label' => 'Name',
                'category' => $this->getReference(
                    self::COMPANY_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'REGISTRATION_ENABLED' => [
                'value' => '1',
                'type' => 'bool',
                'label' => 'Registration authorized',
                'category' => $this->getReference(
                    self::LOGIN_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'BACKGROUND_LOGIN_IMAGE_URL' => [
                'value' => 'https://images.unsplash.com/photo-1496917756835-20cb06e75b4e',
                'type' => 'url',
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
