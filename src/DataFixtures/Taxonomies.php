<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Taxonomy;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Taxonomies implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $root = (new Taxonomy())
            ->setKey('root');

        $manager->persist($root);
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
}
