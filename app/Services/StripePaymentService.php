<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentService implements PaymentGatewayInterface
{
    public function pay(array $data)
    {
        // Set API key
        Stripe::setApiKey(config('services.stripe.secret'));

        $order = $data['order'];
        $user = $data['user'];

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] =
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $item->price * 100,
                        'product_data' => [
                            'name' => $item->product->name,
                        ],
                    ],
                    'quantity' => $item->quantity,
                ];
        }


        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['order_id' => $order->id]),
            'cancel_url' => route('stripe.cancel', ['order_id' => $order->id]),
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ],
        ]);



        return [
            'success' => true,
            'payment_url' => $session->url,
        ];
    }
}
