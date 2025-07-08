<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\PayPal as PaypalClient;

class PaypalPaymentController extends Controller
{
    // Handle Success
    public function handlePaypalSuccess(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $response = $provider->capturePaymentOrder($request->token);

        if ($response['status'] === 'COMPLETED') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
            return response()->json(['message' => 'PayPal payment success!']);
        }

        return response()->json(['message' => 'PayPal payment failed!']);
    }

    
    // Handle Cancel
    public function handlePaypalCancel(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->update(['payment_status' => 'failed']);
        return response()->json(['message' => 'PayPal payment cancelled.']);
    }
}
