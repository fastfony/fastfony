<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\BlameableEntity;
use App\Entity\CommonProperties;
use App\Repository\Product\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\Loggable(logEntryClass: ProductLogEntry::class)]
class Product
{
    use BlameableEntity;
    use CommonProperties\Description;
    use CommonProperties\MetaRobots;
    use CommonProperties\Required\AutoGeneratedId;
    use CommonProperties\Required\Enabled;
    use CommonProperties\Required\Name;
    use CommonProperties\Seo;
    use Stripe\Product;
    use TimestampableEntity;

    #[Gedmo\Slug(fields: ['name'], updatable: false)]
    #[Gedmo\Versioned]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $buttonLabel = null;

    /**
     * @var Collection<int, Price>
     */
    #[ORM\OneToMany(
        targetEntity: Price::class,
        mappedBy: 'product',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    #[Assert\Valid]
    private ?Collection $prices = null;

    /**
     * @var Collection<int, MarketingFeature>
     */
    #[ORM\ManyToMany(targetEntity: MarketingFeature::class, inversedBy: 'products')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private ?Collection $marketingFeatures = null;

    #[ORM\ManyToOne]
    private ?Price $defaultPrice = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->marketingFeatures = new ArrayCollection();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getButtonLabel(): ?string
    {
        return $this->buttonLabel;
    }

    public function setButtonLabel(?string $buttonLabel): static
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): ?Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): static
    {
        $this->prices->removeElement($price);

        return $this;
    }

    /**
     * @return Collection<int, MarketingFeature>
     */
    public function getMarketingFeatures(): ?Collection
    {
        return $this->marketingFeatures;
    }

    public function addMarketingFeature(MarketingFeature $marketingFeature): static
    {
        if (!$this->marketingFeatures->contains($marketingFeature)) {
            $this->marketingFeatures->add($marketingFeature);
        }

        return $this;
    }

    public function removeMarketingFeature(MarketingFeature $marketingFeature): static
    {
        $this->marketingFeatures->removeElement($marketingFeature);

        return $this;
    }

    public function getDefaultPrice(): ?Price
    {
        return $this->defaultPrice;
    }

    public function setDefaultPrice(?Price $defaultPrice): static
    {
        $this->defaultPrice = $defaultPrice;

        return $this;
    }

    #[ORM\PrePersist]
    public function affectDefaultPrice(): void
    {
        if (null === $this->getDefaultPrice()
            && $this->getPrices()
            && 0 < $this->getPrices()->count()) {
            $this->setDefaultPrice($this->getPrices()->first());
        }
    }
}
