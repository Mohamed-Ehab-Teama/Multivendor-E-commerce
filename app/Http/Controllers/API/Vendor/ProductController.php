<?php

namespace App\Http\Controllers\API\Vendor;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ================= Show All Products =================== //
    public function index(Request $request)
    {
        $allProducts = $request->user()->products()->latest()->paginate(10);

        if( count($allProducts) > 0 )
        {
            return ApiResponse::SendResponse(200, "Products Retrieved Successfully", $allProducts);
        }
        return ApiResponse::SendResponse(200, "No Products Found", []);
    }



    // ================= create Product =================== //
    public function store(Request $request, CreateProductRequest $productData)
    {
        $data = $productData->validated();
        
        if( $productData->hasFile('image') )
        {
            $data['image'] = $productData->file('image')->store('products', 'public');
        }

        $product = $request->user()->products()->create($data);

        if($product)
        {
            return ApiResponse::SendResponse(200, 'Product Created Successfully', $data);
        }
    }



    // ================= Show one Product =================== //
    // ================= Update Product =================== //
    // ================= Delete Product =================== //
}
