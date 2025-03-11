<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User\Group;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Users extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return [AppFixtures::GROUP_TEST];
    }

    public function load(ObjectManager $manager): void
    {
        $superAdminGroup = $manager->getRepository(Group::class)
            ->findOneBy(['name' => Group::SUPER_ADMIN_NAME]);

        $user = (new User())
            ->setEmail('superadmin@fastfony.com')
            ->setEnabled(true)
            ->addGroup($superAdminGroup)
        ;

        $manager->persist($user);

        $manager->flush();
    }
}
