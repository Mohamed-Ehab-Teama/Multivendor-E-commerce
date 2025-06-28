<?php

namespace App\Http\Requests\Cart;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $res = ApiResponse::SendResponse(422, 'Validation Errors', $validator->errors());
        throw new ValidationException($validator, $res);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id'        => 'required|exists:products,id',
            'quantity'          => 'required|integer|min:1',
        ];
    }
}
