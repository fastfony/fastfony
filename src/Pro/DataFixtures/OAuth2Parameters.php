<?php

declare(strict_types=1);

namespace App\Pro\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\Parameter\Parameter;
use App\Entity\Parameter\ParameterCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class OAuth2Parameters extends Fixture implements FixtureGroupInterface
{
    private const OAUTH_GITHUB_PARAMETER_CATEGORY = 'OAuth Github';
    private const OAUTH_GOOGLE_PARAMETER_CATEGORY = 'OAuth Google';
    private const PARAMETER_CATEGORIES = [
        self::OAUTH_GITHUB_PARAMETER_CATEGORY,
        self::OAUTH_GOOGLE_PARAMETER_CATEGORY,
    ];

    private const PARAMETER_CATEGORY_REFERENCE_SUFFIX = '_PARAMETER_CATEGORY_REFERENCE';

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
        $parameters = [
            'GITHUB_CLIENT_ID' => [
                'type' => 'text',
                'label' => 'Github Client ID',
                'category' => $this->getReference(
                    self::OAUTH_GITHUB_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'GITHUB_CLIENT_SECRET' => [
                'type' => 'secret',
                'label' => 'Github Client Secret',
                'category' => $this->getReference(
                    self::OAUTH_GITHUB_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'GOOGLE_CLIENT_ID' => [
                'type' => 'text',
                'label' => 'Google Client ID',
                'category' => $this->getReference(
                    self::OAUTH_GOOGLE_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'GOOGLE_CLIENT_SECRET' => [
                'type' => 'secret',
                'label' => 'Google Client Secret',
                'category' => $this->getReference(
                    self::OAUTH_GOOGLE_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
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
