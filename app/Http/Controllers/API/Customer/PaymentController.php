<?php

namespace App\Http\Controllers\API\Customer;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
    public function pay( Request $request)
    {
        $request->validate([
            'payment_type'  => 'required|string|in:stripe,paypal,fawry',
            'amount'        => 'required|numeric|min:1',
        ]);

        $paymentService = PaymentGatewayFactory::make($request->payment_type);
        $result = $paymentService->pay([
            'amount'    => $request->amount,
            'user'      => $request->user(),
        ]);

        return ApiResponse::SendResponse(200, 'Payment Done Successfully', $result);
    }

}
