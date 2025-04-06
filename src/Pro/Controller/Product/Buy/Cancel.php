<?php

declare(strict_types=1);

namespace App\Pro\Controller\Product\Buy;

use App\Pro\Entity\Product\Product;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Cancel extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/product/buy/{id}/cancel', name: 'product_buy_cancel')]
    #[Template('product/buy/cancel.html.twig')]
    public function __invoke(
        Product $price,
    ): array {
        return [];
    }
}
