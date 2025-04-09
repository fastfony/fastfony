<?php

declare(strict_types=1);

namespace App\Pro\Controller\Product\Buy;

use App\Pro\Entity\Product\Price;
use App\Pro\Event\BuySuccess;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Success extends AbstractController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @return array<string, mixed>|RedirectResponse
     */
    #[Route('/product/buy/{id}/success', name: 'product_buy_success')]
    #[Template('pro/product/buy/success.html.twig')]
    public function __invoke(
        Price $price,
        Request $request,
    ): array|RedirectResponse {
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

        return [];
    }
}
