<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Page\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Pages extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createHomepage($manager);
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
}
