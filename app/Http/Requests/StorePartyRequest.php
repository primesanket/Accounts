<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'party_name' => 'required|string|max:255',
            'address' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'party_name.required' => 'The party name field is required.',
            'address.required' => 'The address field is required.',
        ];
    }
}
