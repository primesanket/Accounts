<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_name',
        'owner_name',
        'reference_name',
        'email',
        'mobile_no',
        'office_no',
        'address',
        'state',
        'state_code',
        'pan_no',
        'gst_tin',
    ];
}
