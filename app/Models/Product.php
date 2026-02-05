<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table   = 'products';
    protected $guarded = ['id'];

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function shopData()
    {
        return $this->belongsTo(Shop::class, 'shop', 'id');
    }
}
