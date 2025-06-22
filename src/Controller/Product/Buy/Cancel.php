<?php

declare(strict_types=1);

namespace App\Controller\Product\Buy;

use App\Entity\Product\Price;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Cancel extends AbstractController
{
    #[Route('/product/buy/{id}/cancel', name: 'product_buy_cancel')]
    public function __invoke(
        Price $price,
    ): Response {
        return $this->render('product/buy/cancel.html.twig');
    }
}
