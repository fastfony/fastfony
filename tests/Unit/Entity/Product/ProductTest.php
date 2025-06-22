<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Product;

use App\Entity\Product\MarketingFeature;
use App\Entity\Product\Price;
use App\Entity\Product\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product();
    }

    public function testSetSlug(): void
    {
        $slug = 'mon-super-produit';
        $result = $this->product->setSlug($slug);

        $this->assertSame($slug, $this->product->getSlug());
        $this->assertSame($this->product, $result);
    }

    public function testAddPrice(): void
    {
        $price = $this->createMock(Price::class);

        // Le prix doit être associé au produit
        $price->expects($this->once())
              ->method('setProduct')
              ->with($this->product);

        $result = $this->product->addPrice($price);

        $this->assertTrue($this->product->getPrices()->contains($price));
        $this->assertSame($this->product, $result);
    }

    public function testAddPriceAlreadyAdded(): void
    {
        $price = $this->createMock(Price::class);

        // Ajout initial du prix
        $this->product->addPrice($price);

        // Le prix ne doit pas être associé au produit une seconde fois
        $price->expects($this->never())
              ->method('setProduct');

        $result = $this->product->addPrice($price);

        $this->assertTrue($this->product->getPrices()->contains($price));
        $this->assertCount(1, $this->product->getPrices());
        $this->assertSame($this->product, $result);
    }

    public function testRemovePrice(): void
    {
        $price = $this->createMock(Price::class);

        // Ajout initial du prix
        $this->product->addPrice($price);
        $this->assertTrue($this->product->getPrices()->contains($price));

        $result = $this->product->removePrice($price);

        $this->assertFalse($this->product->getPrices()->contains($price));
        $this->assertSame($this->product, $result);
    }

    public function testAddMarketingFeature(): void
    {
        $feature = $this->createMock(MarketingFeature::class);

        $result = $this->product->addMarketingFeature($feature);

        $this->assertTrue($this->product->getMarketingFeatures()->contains($feature));
        $this->assertSame($this->product, $result);
    }

    public function testAddMarketingFeatureAlreadyAdded(): void
    {
        $feature = $this->createMock(MarketingFeature::class);

        // Ajout initial de la fonctionnalité
        $this->product->addMarketingFeature($feature);

        $result = $this->product->addMarketingFeature($feature);

        $this->assertTrue($this->product->getMarketingFeatures()->contains($feature));
        $this->assertCount(1, $this->product->getMarketingFeatures());
        $this->assertSame($this->product, $result);
    }

    public function testRemoveMarketingFeature(): void
    {
        $feature = $this->createMock(MarketingFeature::class);

        // Ajout initial de la fonctionnalité
        $this->product->addMarketingFeature($feature);
        $this->assertTrue($this->product->getMarketingFeatures()->contains($feature));

        $result = $this->product->removeMarketingFeature($feature);

        $this->assertFalse($this->product->getMarketingFeatures()->contains($feature));
        $this->assertSame($this->product, $result);
    }

    public function testAffectDefaultPriceWhenNoPrices(): void
    {
        $this->assertNull($this->product->getDefaultPrice());

        $this->product->affectDefaultPrice();

        $this->assertNull($this->product->getDefaultPrice());
    }

    public function testAffectDefaultPriceWhenPricesExist(): void
    {
        $price1 = new Price();
        $price2 = new Price();

        $this->product->addPrice($price1);
        $this->product->addPrice($price2);
        $this->assertNull($this->product->getDefaultPrice());

        $this->product->affectDefaultPrice();

        $this->assertSame($price1, $this->product->getDefaultPrice());
    }

    public function testAffectDefaultPriceWhenDefaultPriceAlreadySet(): void
    {
        $price1 = new Price();
        $price2 = new Price();

        $this->product->addPrice($price1);
        $this->product->addPrice($price2);
        $this->product->setDefaultPrice($price2);

        $this->product->affectDefaultPrice();

        $this->assertSame($price2, $this->product->getDefaultPrice());
    }
}
