<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_no' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'product_no.required' => 'The product no field is required.',
            'product_name.required' => 'The product name field is required.',
        ];
    }
}
