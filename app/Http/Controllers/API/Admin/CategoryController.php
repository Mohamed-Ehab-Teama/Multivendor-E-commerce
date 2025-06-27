<?php

namespace App\Http\Controllers\API\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')->get();
        if (count($categories) > 0) {
            return ApiResponse::SendResponse(200, 'Categories Retrieved Successfully', $categories);
        }
        return ApiResponse::SendResponse(200, 'No Categories Found', []);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        if ($category) {
            return ApiResponse::SendResponse(200, "Category created Successfully", $category);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return ApiResponse::SendResponse(200, "Category Retrieved Successfully", $category->load('children'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UpdateCategoryRequest $updateCategoryRequest, Category $category)
    {
        $updateData = $updateCategoryRequest->validated();

        $updateCategory = $category->update($updateData);

        if ($updateCategory) {
            return ApiResponse::SendResponse(200, "Category Updated Successfully", $category);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return ApiResponse::SendResponse(200, "Category Deleted Successfully", []);
    }
}
