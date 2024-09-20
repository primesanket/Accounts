<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
}
