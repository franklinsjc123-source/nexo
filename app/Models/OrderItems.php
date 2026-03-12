<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table   = 'order_items';
    protected $guarded = ['id'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unitData()
    {
        return $this->belongsTo(Unit::class, 'unit'); // unit = unit_id
    }
}
