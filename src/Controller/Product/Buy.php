<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product\Price;
use App\Handler\CheckoutSessionManagement;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class Buy extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CheckoutSessionManagement $checkoutSessionManagement,
    ) {
    }

    /**
     * @return array<string, mixed>|RedirectResponse
     */
    #[Route('/product/buy/{id}', name: 'product_buy')]
    public function __invoke(
        Price $price,
    ): array|RedirectResponse {
        if (!$price->isEnabled()) {
            throw new NotFoundHttpException();
        }

        if (!$price->getStripeId()) {
            $this->logger->error(
                'Someone try to buy a product without a Stripe ID',
                [
                    'entity' => $price::class,
                    'id' => $price->getId(),
                ],
            );

            $this->addFlash('error', 'flash.product.buy.unavailable');

            return $this->redirectToRoute('product_show', ['slug' => $price->getProduct()->getSlug()]);
        }

        try {
            // Redirection to the checkout page on Stripe website
            return $this->redirect(
                $this->checkoutSessionManagement->create(
                    $price,
                )->url
            );
        } catch (ApiErrorException $exception) {
            $this->logger->error(
                'Create Stripe checkout session failed: '.$exception->getMessage(),
                [
                    'entity' => $price::class,
                    'id' => $price->getId(),
                ],
            );

            $this->addFlash('error', 'flash.product.buy.unavailable');

            return $this->redirectToRoute('product_show', ['slug' => $price->getProduct()->getSlug()]);
        }
    }
}
