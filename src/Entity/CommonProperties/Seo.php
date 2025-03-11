<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait Seo
{
    #[Gedmo\Versioned]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[Gedmo\Versioned]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = null;

    /**
     * @var array<string, string>|null
     */
    #[Gedmo\Versioned]
    #[ORM\Column(nullable: true)]
    private ?array $richSnippets = null;

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return array<string, string>|null
     */
    public function getRichSnippets(): ?array
    {
        return $this->richSnippets;
    }

    public function setRichSnippets(?array $richSnippets): static
    {
        $this->richSnippets = $richSnippets;

        return $this;
    }
}
