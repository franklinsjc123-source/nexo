<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_id',
        'razorpay_order_id',
        'amount',
        'transaction_id',
        'payment_status',
        'payment_method',
        'date',
        'time',
        'basic_details'
    ];
}
