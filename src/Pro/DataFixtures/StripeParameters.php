<?php

declare(strict_types=1);

namespace App\Pro\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\Parameter\Parameter;
use App\Entity\Parameter\ParameterCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class StripeParameters extends Fixture implements FixtureGroupInterface
{
    private const STRIPE_PARAMETER_CATEGORY = 'Stripe';
    private const PARAMETER_CATEGORIES = [
        self::STRIPE_PARAMETER_CATEGORY,
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
            'STRIPE_ENABLED' => [
                'type' => 'bool',
                'value' => '0',
                'label' => 'Enabled',
                'help' => 'Enable products and prices synchronization and payments with Stripe',
                'category' => $this->getReference(
                    self::STRIPE_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'STRIPE_API_KEY' => [
                'type' => 'text',
                'label' => 'API Key',
                'help' => 'This is required if Stripe is enabled.',
                'category' => $this->getReference(
                    self::STRIPE_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
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
