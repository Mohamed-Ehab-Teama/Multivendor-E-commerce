<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Helpers\ApiResponse;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentService implements PaymentGatewayInterface
{
    
    public function pay(array $data)
    {
        $order = $data['order'];
        $user = $data['user'];

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => $item->price,
                ],
            ];
        }

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', ['order_id' => $order->id]),
                "cancel_url" => route('paypal.cancel', ['order_id' => $order->id]),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $order->total,
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => "USD",
                                "value" => $order->total,
                            ],
                        ],
                    ],
                    "items" => $items,
                ],
            ],
        ]);

        if (isset($response['id']) && $response['status'] === 'CREATED') {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return [
                        'success' => true,
                        'payment_url' => $link['href'],
                    ];
                }
            }
        }

        return ['success' => false, 'message' => 'PayPal payment failed.'];
    }

}
