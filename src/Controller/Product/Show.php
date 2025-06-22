<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product\Product;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class Show extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/product/{slug:product}', name: 'product_show')]
    #[Template('product/show.html.twig')]
    public function __invoke(
        Product $product,
    ): array {
        if (!$product->isEnabled()) {
            throw new NotFoundHttpException();
        }

        return [
            'product' => $product,
        ];
    }
}
