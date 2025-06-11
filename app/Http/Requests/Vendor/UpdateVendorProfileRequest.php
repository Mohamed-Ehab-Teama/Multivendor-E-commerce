<?php

namespace App\Http\Requests\Vendor;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateVendorProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Failed Validation
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = ApiResponse::SendResponse(422, 'validation Errors', $validator->errors());
        throw new ValidationException($validator, $response);
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'shop_name'     => 'required|string|max:255',
            'shop_slug'     => 'required|alpha_dash|unique:vendor_profiles,shop_slug,'. optional($request->user()->vendorProfile())->id,
            'description'   => 'string|nullable',
            'address'       => 'string|nullable',
        ];
    }
}
