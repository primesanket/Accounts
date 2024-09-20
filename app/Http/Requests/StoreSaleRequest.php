<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bill_no' => 'nullable|string|max:20',
            'firm_id' => 'required|integer',
            'bill_type_id' => 'required|integer',
            'party_id' => 'required|integer',
            'currency_id' => 'required|integer',
            'location_id' => 'required|string',
            'sale_date' => 'required|date',
            'days' => 'required|integer',
            'due_date' => 'nullable|date|required_with:sale_date',
            'ex_rate' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'bill_no.required' => 'The bill number field is required.',
            'bill_no.string' => 'The bill number must be a string.',
            'bill_no.max' => 'The bill number may not be greater than 20 characters.',
            'firm_id.required' => 'The firm field is required.',
            'firm_id.integer' => 'The firm must be an integer.',
            'bill_type_id.required' => 'The bill type field is required.',
            'bill_type_id.integer' => 'The bill type must be an integer.',
            'party_id.required' => 'The party field is required.',
            'party_id.integer' => 'The party must be an integer.',
            'currency_id.required' => 'The currency field is required.',
            'currency_id.integer' => 'The currency must be an integer.',
            'location_id.required' => 'The location field is required.',
            'location_id.integer' => 'The location must be an integer.',
            'location_id.string' => 'The location must be an string.',
            'sale_date.required' => 'The sale date field is required.',
            'sale_date.date' => 'The sale date must be a valid date.',
            'days.required' => 'The days field is required.',
            'days.integer' => 'The days must be an integer.',
            'due_date.required' => 'The due date field is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'ex_rate.required' => 'The exchange rate field is required.',
            'ex_rate.numeric' => 'The exchange rate must be a number.',
        ];
    }
}
