<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Collection\RecordCollection;
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
            ->setTitle('Go fast!')
            ->setTemplate('pages/custom/example_homepage.html.twig')
            ->addRecordCollection($this->getReference('features', RecordCollection::class))
            ->addRecordCollection($this->getReference('use-cases', RecordCollection::class))
            ->setEnabled(true)
            ->setPublished(true)
        ;
        $manager->persist($homepage);

        $privacyPolicy = (new Page())
            ->setName('Privacy policy')
            ->setTitle('Privacy policy')
            ->setContent('<p>This website does not collect any personal data by default.</p>
                <p>However, if the contact form feature is enabled and used, we may collect the following information:</p>
                <ul>
                    <li>First Name</li>
                    <li>Last Name</li>
                    <li>Email Address</li>
                    <li>Phone Number</li>
                    <li>Message Content</li>
                </ul>
                <p>This information is used solely to respond to inquiries and is not stored or shared beyond this purpose.</p>
                <p>We also use a technical cookie necessary for the proper functioning of the website. This cookie does not track or collect any personal data.</p>
                <p>By using this website, you acknowledge and accept this privacy policy.</p>
            ')
            ->setEnabled(true)
            ->setPublished(true)
        ;

        $manager->persist($privacyPolicy);

        $company = (new Page())
            ->setName('Company')
            ->setTitle('About us')
            ->setSlug('company')
        ;

        $manager->persist($company);
    }
}
