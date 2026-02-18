<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $table   = 'shop';
    protected $guarded = ['id'];

    // public function categoryData()
    // {
    //     return $this->belongsTo(Category::class, 'category', 'id');
    // }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



    public function getCategoryNamesAttribute()
    {
        if (!$this->category) {
            return '-';
        }

        $ids = explode(',', $this->category);

        return Category::whereIn('id', $ids)
            ->pluck('category_name')
            ->implode(', ');
    }
}
