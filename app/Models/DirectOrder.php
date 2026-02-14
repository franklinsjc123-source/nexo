<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectOrder extends Model
{
    use HasFactory;
    protected $table   = 'direct_orders';
    protected $guarded = ['id'];

    public function shopData()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }


     public function userData()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
