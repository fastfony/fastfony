<?php

declare(strict_types=1);

namespace App\Pro\Controller\Product\Buy;

use App\Pro\Entity\Product\Price;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Cancel extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    #[Route('/product/buy/{id}/cancel', name: 'product_buy_cancel')]
    #[Template('pro/product/buy/cancel.html.twig')]
    public function __invoke(
        Price $price,
    ): array {
        return [];
    }
}
