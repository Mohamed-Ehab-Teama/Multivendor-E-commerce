<?php

namespace App\Http\Controllers\API\Vendor;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ================= Show All Products =================== //
    public function index(Request $request)
    {
        $allProducts = $request->user()->products()->latest()->paginate(10);

        if (count($allProducts) > 0) {
            return ApiResponse::SendResponse(200, "Products Retrieved Successfully", $allProducts);
        }
        return ApiResponse::SendResponse(200, "No Products Found", []);
    }



    // ================= create Product =================== //
    public function store(Request $request, CreateProductRequest $productData)
    {
        $data = $productData->validated();

        if ($productData->hasFile('image')) {
            $data['image'] = $productData->file('image')->store('products', 'public');
        }

        $product = $request->user()->products()->create($data);

        if ($product) {
            return ApiResponse::SendResponse(200, 'Product Created Successfully', $data);
        }
    }



    // ================= Show one Product =================== //
    public function show(Request $request, Product $product)
    {
        if ($product) {
            return ApiResponse::SendResponse(200, 'Product Retrieved Successfully', $product);
        }
        return ApiResponse::SendResponse(200, 'No Product Found', []);
    }



    // ================= Update Product =================== //
    public function update(UpdateProductRequest $updateProduct, Product $product)
    {
        $data = $updateProduct->validated();

        
        if ($updateProduct->hasFile('image')) {
            // Delete The Old Image
            if ( $product->image && Storage::disk('public')->exists($product->image) )
            {
                Storage::disk('public')->delete($product->image);
            }

            // Save the New Image
            $data['image'] = $updateProduct->file('image')->store('products', 'public');
        }

        $product->update($data);

        return ApiResponse::SendResponse(200, 'Updated Successfully', $product);
    }



    // ================= Delete Product =================== //
    public function destroy (Product $product)
    {
        if ($product)
        {
            // Delete The product's Image
            if ( $product->image && Storage::disk('public')->exists($product->image) )
            {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return ApiResponse::SendResponse(200, 'Product Deleted Successfully', []);
        }
    }

}
