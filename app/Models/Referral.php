<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;
    protected $table   = 'referral';
    protected $guarded = ['id'];

    public function users()
{
    return $this->hasMany(User::class, 'referral_code', 'referral_code');
}

}
