<?php

declare(strict_types=1);

namespace App\Controller\Product\Buy;

use App\Entity\Product\Price;
use App\Event\BuySuccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Success extends AbstractController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[Route('/product/buy/{id}/success', name: 'product_buy_success')]
    public function __invoke(
        Price $price,
        Request $request,
    ): Response {
        if (null === $request->query->get('session_id')) {
            return $this->redirectToRoute('product_buy_cancel', ['id' => $price->getId()]);
        }

        // We dispatch a success event to handle the purchase
        $this->eventDispatcher->dispatch(
            new BuySuccess(
                $price,
                $request->query->get('session_id'),
            ),
        );

        $this->addFlash('success', 'flash.product.buy.success');

        return $this->render('product/buy/success.html.twig');
    }
}
