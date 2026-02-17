<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $table   = 'shop';
    protected $guarded = ['id'];

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

       public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
