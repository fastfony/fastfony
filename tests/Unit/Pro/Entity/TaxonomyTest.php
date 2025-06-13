<?php

declare(strict_types=1);

namespace App\Tests\Pro\Entity;

use App\Pro\Entity\Taxonomy;
use App\Tests\Unit\Entity\ToStringTestTrait;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TaxonomyTest extends TestCase
{
    use ToStringTestTrait;

    private Taxonomy $taxonomy;

    protected function setUp(): void
    {
        $this->taxonomy = new Taxonomy();
    }

    public function testToString(): void
    {
        $this->toStringTest(
            Taxonomy::class,
            'key',
        );
    }

    public function testGetSetSlug(): void
    {
        $slug = 'category-slug';
        $result = $this->taxonomy->setSlug($slug);

        $this->assertSame($slug, $this->taxonomy->getSlug());
        $this->assertSame($this->taxonomy, $result, 'La méthode doit retourner $this');
    }

    public function testGetId(): void
    {
        // Par défaut, l'ID est null pour une nouvelle entité
        $this->assertNull($this->taxonomy->getId());

        // Simulation d'un ID attribué
        $reflection = new \ReflectionClass($this->taxonomy);
        $property = $reflection->getProperty('id');
        $property->setValue($this->taxonomy, 42);

        $this->assertSame(42, $this->taxonomy->getId());
    }

    public function testGetSetKey(): void
    {
        $key = 'product-category';
        $result = $this->taxonomy->setKey($key);

        $this->assertSame($key, $this->taxonomy->getKey());
        $this->assertSame($this->taxonomy, $result, 'La méthode doit retourner $this');
    }

    public function testGetChildren(): void
    {
        // Vérifier que children est initialisé comme ArrayCollection vide
        $children = $this->taxonomy->getChildren();

        $this->assertInstanceOf(ArrayCollection::class, $children);
        $this->assertCount(0, $children);
    }
}
