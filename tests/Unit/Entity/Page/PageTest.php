<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Page;

use App\Entity\Page\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testGetId(): void
    {
        $page = (new Page());

        $this->assertNull($page->getId());
    }

    public function testGetMetaRobotsDirectives(): void
    {
        $page = (new Page());

        $this->assertSame(
            'index, follow',
            implode(', ', $page->getMetaRobotsDirectives()),
        );

        $page->setNofollow(true);
        $page->setNoindex(true);

        $this->assertSame(
            'noindex, nofollow',
            implode(', ', $page->getMetaRobotsDirectives()),
        );

        $page->setNoarchive(true);
        $page->setNosnippet(true);
        $page->setNotranslate(true);
        $page->setNoimageindex(true);

        $this->assertSame(
            'noindex, nofollow, noarchive, nosnippet, notranslate, noimageindex',
            implode(', ', $page->getMetaRobotsDirectives()),
        );
    }
}
