<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;

class StripePaymentService implements PaymentGatewayInterface
{
    public function pay(array $data)
    {
        // 
    }
}