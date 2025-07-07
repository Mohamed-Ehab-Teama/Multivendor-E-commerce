<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Order;
use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Services\PaymentGatewayFactory;

class OrderController extends Controller
{

    // Get All Users' Orders Products
    public function index(Request $request)
    {
        // get user
        $user = $request->user();

        // Get User's Orders
        $AllOrders = $user->orders()->with('items.product')->get();

        return ApiResponse::SendResponse(200, "Orders Retrieved Successfully", $AllOrders);
    }



    // Show Single Order
    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        $showOrder = $user->orders()->with('items.product')->findOrFail($order->id);

        return ApiResponse::SendResponse(200, "Order Retrieved Successfully", $showOrder);
    }




    // Place Order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_type'  => 'required|string',
        ]);

        // get user
        $user = $request->user();
        // Get Cart Items
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return ApiResponse::SendResponse(422, "Cart is empty", []);
        }

        // Start DB transaction
        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->quantity) {
                    return ApiResponse::SendResponse(422, "Out Of Stock for {$item->product->name}", []);
                }

                $total += $item->quantity * $item->product->price;
            }

            // Create Order
            $order = Order::create([
                'user_id'       => $user->id,
                'status'        => 'pending',
                'total'         => $total,
            ]);

            // Create order-Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $item->product_id,
                    'quantity'          => $item->quantity,
                    'price'             => $item->product->price,
                ]);

                // Reduce Product Stock
                $item->product->decrement('quantity', $item->quantity);
            }

            // Clear User's Cart
            $user->cart()->delete();


            // Create Stripe Payment Session
            $paymentService = PaymentGatewayFactory::make($request->payment_type);
            $paymentResult = $paymentService->pay([
                'order' => $order,
                'user' => $user,
            ]);
            // Create Stripe Payment Session End

            // dd($paymentResult);

            // Commit Changes to DB
            DB::commit();

            return ApiResponse::SendResponse(200, 'Order Made Successfully', $paymentResult['payment_url']);
            
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::SendResponse(500, 'Order Made Successfully', $e->getMessage());
        }
    }
}
