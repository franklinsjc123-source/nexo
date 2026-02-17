<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectOrderItems extends Model
{
    use HasFactory;
    protected $table   = 'direct_order_items';
    protected $guarded = ['id'];


}
