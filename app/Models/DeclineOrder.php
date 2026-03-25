<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclineOrder extends Model
{
    use HasFactory;
    protected $table   = 'decline_order';
    protected $guarded = ['id'];

}
