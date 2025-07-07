<?php

namespace App\Http\Controllers\API\Customer;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function handleStripeSuccess (Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return ApiResponse::SendResponse(200, "Order Paid Successfully", $order->id);
    }
    
    
    
    public function handleStripeCancel (Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        
        $order->update([
            'payment_status' => 'failed',
        ]);

        return ApiResponse::SendResponse(200, "Order Payment Canceled", $order->id);
    }


}
