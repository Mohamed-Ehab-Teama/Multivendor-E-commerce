<?php

namespace App\Http\Requests\Products;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    // If failed Validation
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = ApiResponse::SendResponse(422, 'Validation Errors', $validator->errors());
        throw new ValidationException($validator, $response);
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|unique:products,slug,' . $this->product->id,
            // 'slug'          => 'required|string|' .
            //     Rule::unique('products', 'slug')->ignore($this->product),
            'price'         => 'required|numeric',
            'quantity'      => 'required|integer',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'category_id'   => 'required|exists:categories,id',
        ];
    }
}
