<?php

declare(strict_types=1);

namespace App\Tests\Integration\Pro\Controller\Api\Taxonomy;

use App\Pro\Controller\Api\Taxonomy\GetCollection;
use App\Pro\Repository\TaxonomyRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetCollectionTest extends KernelTestCase
{
    private GetCollection $controller;
    private MockObject|TaxonomyRepository $taxonomyRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->controller = new GetCollection();
        $this->controller->setContainer($container);

        $this->taxonomyRepository = $this->createMock(TaxonomyRepository::class);
    }

    public function testInvoke(): void
    {
        $hierarchyData = [
            ['id' => 1, 'key' => 'category1', '__children' => []],
            ['id' => 2, 'key' => 'category2', '__children' => [
                ['id' => 3, 'key' => 'subcategory1'],
            ]],
        ];

        $this->taxonomyRepository
            ->expects($this->once())
            ->method('childrenHierarchy')
            ->willReturn($hierarchyData);

        $response = $this->controller->__invoke($this->taxonomyRepository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('entities', $content);
        $this->assertEquals($hierarchyData, $content['entities']);
    }
}
