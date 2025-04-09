<?php

declare(strict_types=1);

namespace App\Pro\Handler;

use App\Pro\Entity\Order;
use App\Pro\Entity\Product\Price;
use App\Pro\Repository\OrderRepository;
use Stripe\Checkout\Session;

class OrderManagement
{
    public function __construct(
        private readonly CustomerManagement $customerManagement,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function create(
        Price $price,
        Session $session,
    ): Order {
        $customer = $this->customerManagement->findOrCreate(
            $session->customer_details,
            $session->customer,
        );

        $order = $this->orderRepository->findOneBy(['stripeId' => $session->id]);
        if (null === $order) {
            $order = (new Order())
                ->setCustomer($customer)
                ->setPrice($price)
            ;
        }

        $order->updateFromStripe($session);
        $this->orderRepository->save($order);

        return $order;
    }
}
