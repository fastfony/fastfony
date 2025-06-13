<?php

declare(strict_types=1);

namespace App\Pro\Controller\Product;

use App\Pro\Repository\Product\ProductRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[Route(
        '/products',
        name: 'product_index',
        methods: ['GET'],
    )]
    #[Template('pro/product/index.html.twig')]
    public function __invoke(): array
    {
        return [
            'products' => $this->productRepository->findBy(['enabled' => true], ['name' => 'ASC']),
        ];
    }
}
