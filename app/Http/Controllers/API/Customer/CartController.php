<?php

namespace App\Http\Controllers\API\Customer;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Models\Product;
use Intervention\Image\Colors\Rgb\Channels\Red;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with('product')->get();
        if (count($cart) > 0) {
            return ApiResponse::SendResponse(200, 'Cart Data Retrieed Successfully', $cart);
        }
        return ApiResponse::SendResponse(200, 'Not Data Available', []);
    }



    public function add(Request $request, AddToCartRequest $addToCartRequest)
    {
        $data = $addToCartRequest->validated();

        $cartItem = $request->user()->cart()->where('product_id', $data['product_id'])->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } 
        else 
        {
            $cartItem = $request->user()->cart()->create([
                'product_id'    => $data['product_id'],
                'quantity'      => $data['quantity'],
            ]);
        }

        return ApiResponse::SendResponse(200, 'Added To Cart Successfully', $cartItem);
    }



    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $request->user()->cart()->where('product_id', $product->id)->firstOrFail();
        $cartItem->update(['quantity' => $request->quantity]);

        return ApiResponse::SendResponse(200, 'Cart Updated Successfully', $cartItem);
    }



    public function remove(Request $request, Product $product)
    {
        $removeItem = $request->user()->cart()->where('product_id', $product->id)->delete();

        if ($removeItem)
        {
            return ApiResponse::SendResponse(200, 'Cart Item Deleted Successfully', []);
        }
        return ApiResponse::SendResponse(200, 'Cart Item Not Found', []);
        
    }
}
