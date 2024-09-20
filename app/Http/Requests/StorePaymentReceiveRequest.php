<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentReceiveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'firm_id' => 'required|integer|exists:firms,id',
            'party_id' => 'required|integer|exists:parties,id',
            'sale_id' => 'required|integer|exists:sales,id',
            'sale_date' => 'required|date',
            'date' => 'required|date',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'expense_account_id' => 'required|integer|exists:expense_accounts,id',
            'discount' => 'nullable|numeric|min:0',
            'amount' => 'required|numeric|min:1',
            'remark' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'firm_id.required' => 'The firm is required.',
            'party_id.required' => 'The party is required.',
            'sale_id.required' => 'The bill no is required.',
            'sale_date.required' => 'The sale date is required.',
            'date.required' => 'The date is required.',
            'payment_type_id.required' => 'The payment type is required.',
            'expense_account_id.required' => 'The receiver is required.',
            'discount.numeric' => 'The discount must be a valid number.',
            'amount.required' => 'The amount is required.',
            'remark.string' => 'The remark must be a string.',
        ];
    }
}
