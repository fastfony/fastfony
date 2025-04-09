<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User\Group;
use App\Entity\User\Role;
use App\Entity\User\RoleCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GroupsAndRoles extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createGroupsAndRoles($manager);
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

    private function createGroupsAndRoles(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_API' => 'Api access',
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

        $roleCategory = [
            'General' => [
                $this->getReference('ROLE_API', Role::class),
                $this->getReference('ROLE_USER', Role::class),
                $this->getReference('ROLE_ADMIN', Role::class),
                $this->getReference('ROLE_SUPER_ADMIN', Role::class),
            ],
            'Facilities in admin' => [
                $this->getReference('ROLE_ALLOWED_TO_SWITCH', Role::class),
            ],
        ];

        foreach ($roleCategory as $key => $roleReferences) {
            $roleCategory = (new RoleCategory())
                ->setName($key);

            foreach ($roleReferences as $roleReference) {
                $roleCategory->addRole($roleReference);
            }

            $manager->persist($roleCategory);
        }

        $groups = [
            'User' => [
                $this->getReference('ROLE_API', Role::class),
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
                ->setName($key)
                ->setOnRegistration('User' === $key);

            foreach ($roles as $roleCategory) {
                $group->addRole($roleCategory);
            }
            $manager->persist($group);
        }
    }
}
