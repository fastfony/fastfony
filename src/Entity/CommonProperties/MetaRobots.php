<?php

declare(strict_types=1);

namespace App\Entity\CommonProperties;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait MetaRobots
{
    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $noindex = false;

    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $nofollow = false;

    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $noarchive = false;

    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $nosnippet = false;

    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $notranslate = false;

    #[Gedmo\Versioned]
    #[ORM\Column(options: ['default' => false])]
    private bool $noimageindex = false;

    public function isNoindex(): bool
    {
        return $this->noindex;
    }

    public function setNoindex(bool $noindex): static
    {
        $this->noindex = $noindex;

        return $this;
    }

    public function isNofollow(): bool
    {
        return $this->nofollow;
    }

    public function setNofollow(bool $nofollow): static
    {
        $this->nofollow = $nofollow;

        return $this;
    }

    public function isNoarchive(): bool
    {
        return $this->noarchive;
    }

    public function setNoarchive(bool $noarchive): static
    {
        $this->noarchive = $noarchive;

        return $this;
    }

    public function isNosnippet(): bool
    {
        return $this->nosnippet;
    }

    public function setNosnippet(bool $nosnippet): static
    {
        $this->nosnippet = $nosnippet;

        return $this;
    }

    public function isNotranslate(): bool
    {
        return $this->notranslate;
    }

    public function setNotranslate(bool $notranslate): static
    {
        $this->notranslate = $notranslate;

        return $this;
    }

    public function isNoimageindex(): bool
    {
        return $this->noimageindex;
    }

    public function setNoimageindex(bool $noimageindex): static
    {
        $this->noimageindex = $noimageindex;

        return $this;
    }

    public function getMetaRobotsDirectives(): array
    {
        $directives = [
            $this->noindex ? 'noindex' : 'index',
            $this->nofollow ? 'nofollow' : 'follow',
        ];

        if ($this->noarchive) {
            $directives[] = 'noarchive';
        }
        if ($this->nosnippet) {
            $directives[] = 'nosnippet';
        }
        if ($this->notranslate) {
            $directives[] = 'notranslate';
        }
        if ($this->noimageindex) {
            $directives[] = 'noimageindex';
        }

        return $directives;
    }
}
