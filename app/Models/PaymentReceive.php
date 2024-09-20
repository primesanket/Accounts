<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id',
        'party_id',
        'sale_id',
        'sale_date',
        'date',
        'payment_type_id',
        'expense_account_id',
        'discount',
        'amount',
        'remark',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function expenseAccount()
    {
        return $this->belongsTo(ExpenseAccount::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}
