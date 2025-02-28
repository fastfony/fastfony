<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Page;
use App\Entity\Parameter;
use App\Entity\ParameterCategory;
use App\Entity\Role;
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
        $this->createHomepage($manager);
        $this->createGroupsAndRoles($manager);
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
                'type' => 'email',
                'label' => 'Sender email address',
                'help' => 'This e-mail must be authorize by server configure on MAILER_DSN in .env.local',
                'category' => $this->getReference(
                    self::EMAIL_PARAMETER_CATEGORY.self::PARAMETER_CATEGORY_REFERENCE_SUFFIX,
                    ParameterCategory::class
                ),
            ],
            'COMPANY_ICON_FILEPATH' => [
                'value' => '/images/Fastfony-icon.svg',
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
                'value' => 'https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80',
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
                ->setValue($values['value'])
                ->setType($values['type'])
                ->setLabel($values['label'])
                ->setHelp($values['help'] ?? null)
            ;

            if (isset($values['category'])) {
                $parameter->setCategory($values['category']);
            }

            $manager->persist($parameter);
        }
    }

    private function createHomepage(ObjectManager $manager): void
    {
        $homepage = (new Page())
            ->setHomepage(true)
            ->setName('Homepage')
            ->setTitle('Welcome on Fastfony!')
            ->setEnabled(true)
        ;
        $manager->persist($homepage);
    }

    private function createGroupsAndRoles(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_USER' => 'User',
            'ROLE_ADMIN' => 'Administrator',
            'ROLE_ALLOWED_TO_SWITCH' => 'Allowed to switch',
            'ROLE_SUPER_ADMIN' => 'Super Administrator',
        ];

        foreach ($roles as $key => $description) {
            $role = (new Role())
                ->setName($key)
                ->setDescription($description);
            $manager->persist($role);
            $this->addReference(
                $key,
                $role
            );
        }

        $groups = [
            'User' => [
                $this->getReference('ROLE_USER', Role::class),
            ],
            'Administrator' => [
                $this->getReference('ROLE_USER', Role::class),
                $this->getReference('ROLE_ADMIN', Role::class),
            ],
            Group::SUPER_ADMIN_NAME => [
                $this->getReference('ROLE_USER', Role::class),
                $this->getReference('ROLE_ADMIN', Role::class),
                $this->getReference('ROLE_ALLOWED_TO_SWITCH', Role::class),
                $this->getReference('ROLE_SUPER_ADMIN', Role::class),
            ],
        ];

        foreach ($groups as $key => $roles) {
            $group = (new Group())
                ->setName($key);

            foreach ($roles as $role) {
                $group->addRole($role);
            }
            $manager->persist($group);
        }
    }
}
