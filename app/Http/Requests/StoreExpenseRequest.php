<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expense_type_id' => 'required|integer',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date|date_format:Y-m-d',
            'expense_account_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'expense_type_id.required' => 'The type field is required.',
            'amount.required' => 'The amount field is required.',
            'expense_date.required' => 'The date field is required.',
            'expense_account_id.required' => 'The account by field is required.',
        ];
    }
}
