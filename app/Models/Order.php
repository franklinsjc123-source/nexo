<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table   = 'orders';
    protected $guarded = ['id'];


    public function customerData()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }


    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
