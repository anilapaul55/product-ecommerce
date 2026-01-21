<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class coupon extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'type',
        'value',
        'expiry_date',
        'min_amount',
    ];
}
