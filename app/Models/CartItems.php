<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit',
        'price',
        'discount_price',
        'total_price'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


     public function unitData()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }



}
