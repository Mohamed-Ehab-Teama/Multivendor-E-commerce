<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use Exception;

class PaymentGatewayFactory 
{

    public static function make ( string $gateway ) : PaymentGatewayInterface
    {
        return match( $gateway )
        {
            'stripe'    => new StripePaymentService,
            'paypal'    => new PaypalPaymentService,
            'fawry'     => new FawryPaymentService,
            default     => throw new Exception('UnSupported Payment Method'),
        };
    }

}