<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'amount',
        'expense_type_id',
        'description',
        'expense_account_id',
    ];

    public function expense_type()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function expense_account()
    {
        return $this->belongsTo(ExpenseAccount::class);
    }
}
