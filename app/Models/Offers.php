<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;
    protected $table   = 'offers';
    protected $guarded = ['id'];

      public function shopData()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }


}
