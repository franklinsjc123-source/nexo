<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $table='permissions';

    protected $guarded = ['id'];
  //      public $guarded = [];

   protected $fillable = [
        'category',
        'name',
        'display_name',
    ];
}
