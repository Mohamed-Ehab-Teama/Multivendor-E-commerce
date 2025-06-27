<?php

namespace App\Http\Controllers\API\Public;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // =================== All Public Products
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category'])->where('is_active', true);

        if ( $request->has('category_id') )
        {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(12);

        return ApiResponse::SendResponse(200, "Products Retrieved Successfully", $products);
    }



    // =================== One Public Products
    public function show($slug)
    {
        $product = Product::with(['vendor', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->get();

        return ApiResponse::SendResponse(200, 'Product Retrieved Successfully', $product);
    }
}
