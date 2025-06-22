<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Repository\Product\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Index extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    #[Route(
        '/products',
        name: 'product_index',
        methods: ['GET'],
    )]
    public function __invoke(): Response
    {
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $this->productRepository->findBy(['enabled' => true], ['name' => 'ASC']),
            ],
        );
    }
}
