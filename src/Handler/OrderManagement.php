<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Order;
use App\Entity\Product\Price;
use App\Repository\OrderRepository;
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
