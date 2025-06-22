<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product\MarketingFeature;
use App\Entity\Product\Price;
use App\Entity\Product\Product as ProductEntity;
use App\Enum\RecurringInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Product extends Fixture implements FixtureGroupInterface
{
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

    public function load(ObjectManager $manager): void
    {
        $marketingFeatures = ['Trunk', 'Big ears', 'Subscription'];

        foreach ($marketingFeatures as $marketingFeature) {
            $marketingFeature = (new MarketingFeature())
                ->setName($marketingFeature)
                ->setEnabled(true);

            $manager->persist($marketingFeature);
            $this->addReference('mf_'.$marketingFeature, $marketingFeature);
        }

        $price = (new Price())
            ->setEnabled(true)
            ->setUnitAmount(39.9)
            ->setCurrency('EUR')
        ;
        $product = (new ProductEntity())
            ->setName('ElePHPant of the month')
            ->setEnabled(true)
            ->setDescription("The ElePHPant is the adorable, elephantine mascot of the PHP project.
            Occasionally, official stuffed toy elePHPants designed by Vincent Pontier are made available.
            You may have seen pictures of them (from Flickr) at the bottom of the php.net homepage. Beware of imitators.
            (This is an example product, we don't sell elePHPants...)")
            ->addMarketingFeature($this->getReference('mf_Trunk', MarketingFeature::class))
            ->addMarketingFeature($this->getReference('mf_Big ears', MarketingFeature::class))
            ->setDefaultPrice($price)
            ->addPrice($price)
        ;
        $manager->persist($product);

        $secondPrice = (new Price())
            ->setEnabled(true)
            ->setUnitAmount(29.9)
            ->setCurrency('EUR')
            ->setRecurringInterval(RecurringInterval::Month)
            ->setRecurringIntervalCount(1)
            ->setRecurringTrialPeriodDays(0)
        ;
        $secondProduct = (new ProductEntity())
            ->setName('1 ElePHPant each month')
            ->setEnabled(true)
            ->setDescription("The ElePHPant is the adorable, elephantine mascot of the PHP project.
            Occasionally, official stuffed toy elePHPants designed by Vincent Pontier are made available.
            You may have seen pictures of them (from Flickr) at the bottom of the php.net homepage. Beware of imitators.
            (This is an example product, we don't sell elePHPants...)")
            ->addMarketingFeature($this->getReference('mf_Trunk', MarketingFeature::class))
            ->addMarketingFeature($this->getReference('mf_Big ears', MarketingFeature::class))
            ->addMarketingFeature($this->getReference('mf_Subscription', MarketingFeature::class))
            ->setDefaultPrice($secondPrice)
            ->addPrice($secondPrice)
        ;
        $manager->persist($secondProduct);

        $thirdPrice = (new Price())
            ->setEnabled(true)
            ->setUnitAmount(45.9)
            ->setCurrency('EUR')
            ->setRecurringInterval(RecurringInterval::Month)
            ->setRecurringIntervalCount(1)
            ->setRecurringTrialPeriodDays(0)
        ;
        $thirdProduct = (new ProductEntity())
            ->setName('2 ElePHPant each month')
            ->setEnabled(true)
            ->setDescription("The ElePHPant is the adorable, elephantine mascot of the PHP project.
            Occasionally, official stuffed toy elePHPants designed by Vincent Pontier are made available.
            You may have seen pictures of them (from Flickr) at the bottom of the php.net homepage. Beware of imitators.
            (This is an example product, we don't sell elePHPants...)")
            ->addMarketingFeature($this->getReference('mf_Trunk', MarketingFeature::class))
            ->addMarketingFeature($this->getReference('mf_Big ears', MarketingFeature::class))
            ->addMarketingFeature($this->getReference('mf_Subscription', MarketingFeature::class))
            ->setDefaultPrice($thirdPrice)
            ->addPrice($thirdPrice)
        ;
        $manager->persist($thirdProduct);
        $manager->flush();
    }
}
