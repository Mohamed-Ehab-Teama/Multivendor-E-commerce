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

        // Get Authenticated User & other data sent through the request
        $user = $request->user();
        $product_id = $data['product_id'];
        $quantityToAdd = $data['quantity'];

        // Get The targeted product
        $product = Product::findOrFail($product_id);

        // Get the Cart Item
        $existingCartItem = $user->cart()->where('product_id', $product_id)->first();
        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalQuantity = $existingQuantity + $quantityToAdd;

        // Check on quantity
        if ($totalQuantity > $product->quantity)
        {
            return ApiResponse::SendResponse(200, 'Not Enough Stock', []);
        }

        // Add or Update the quantity
        if ($existingCartItem) 
        {
            $existingCartItem->quantity += $data['quantity'];
            $existingCartItem->save();
        } 
        else 
        {
            $existingCartItem = $user->cart()->create([
                'product_id'    => $data['product_id'],
                'quantity'      => $data['quantity'],
            ]);
        }

        return ApiResponse::SendResponse(200, 'Added To Cart Successfully', $existingCartItem);
    }



    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $updateQuantity = $request->quantity;

        $cartItem = $user->cart()->where('product_id', $product->id)->firstOrFail();

        // Check on the Quantity
        if ( $updateQuantity > $product->quantity )
        {
            return ApiResponse::SendResponse(200, 'Not Enough Stock', []);
        }

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
