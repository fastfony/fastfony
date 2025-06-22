<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class Show extends AbstractController
{
    #[Route('/product/{slug:product}', name: 'product_show')]
    public function __invoke(
        Product $product,
    ): Response {
        if (!$product->isEnabled()) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'product/show.html.twig',
            [
                'product' => $product,
            ],
        );
    }
}
