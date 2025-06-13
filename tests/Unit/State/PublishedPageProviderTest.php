<?php

declare(strict_types=1);

namespace App\Tests\Unit\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use App\State\PublishedPageProvider;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PublishedPageProviderTest extends TestCase
{
    public function testProvideForGetOperation(): void
    {
        $page = $this->createMock(Page::class);
        $page->expects(self::once())
            ->method('isPublished')
            ->willReturn(true)
        ;

        $pageRepository = $this->createMock(PageRepository::class);
        $pageRepository
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturnCallback(function ($id) use ($page) {
                if (1 === $id) {
                    return $page;
                }

                return null;
            })
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with(Page::class)
            ->willReturn($pageRepository);

        $publishedPageProvider = new PublishedPageProvider(
            $entityManager,
        );
        $actual = $publishedPageProvider->provide(
            $this->createMock(Get::class),
            ['id' => 1]
        );
        $this->assertSame($page, $actual);

        $this->expectException(NotFoundHttpException::class);
        $publishedPageProvider->provide(
            $this->createMock(Get::class),
            ['id' => 2]
        );
    }

    public function testProvideForGetCollectionOperation(): void
    {
        $pageRepository = $this->createMock(PageRepository::class);
        $pageRepository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn([])
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with(Page::class)
            ->willReturn($pageRepository);

        $publishedPageProvider = new PublishedPageProvider(
            $entityManager,
        );
        $actual = $publishedPageProvider->provide(
            $this->createMock(GetCollection::class),
        );
        $this->assertSame([], $actual);
    }
}
