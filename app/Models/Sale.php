<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no',
        'firm_id',
        'bill_type_id',
        'party_id',
        'currency_id',
        'location_id',
        'sale_date',
        'days',
        'due_date',
        'ex_rate',
        'total',
        'cgst',
        'sgst',
        'igst',
        'grand_total',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function billType()
    {
        return $this->belongsTo(BillType::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function location($is_option = false)
    {
        if ($is_option) {
            return $this->location_id === 'GUJARAT' ? ['id' => 'GUJARAT', 'text' => 'Gujarat'] : ['id' => 'OUT_OF_GUJARAT', 'text' => 'Out Of Gujarat'];
        } else {
            return $this->location_id === 'GUJARAT' ? 'Gujarat' : 'Out Of Gujarat';
        }
    }

    public function payments()
    {
        return $this->hasMany(PaymentReceive::class);
    }
}
