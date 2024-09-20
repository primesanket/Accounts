<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_no',
        'product_name',
        'company_name',
        'gst',
        'hsn_sac',
        'opening_stock',
        'remark',
    ];
}
