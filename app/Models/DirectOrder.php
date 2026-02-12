<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectOrder extends Model
{
    use HasFactory;
    protected $table   = 'direct_orders';
    protected $guarded = ['id'];

}
