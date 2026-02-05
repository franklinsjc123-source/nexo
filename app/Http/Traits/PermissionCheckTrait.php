<?php

namespace App\Http\Traits;

trait PermissionCheckTrait
{
    public function checkPermission($permission)
    {
        // $user = auth()->user();
        // $check = (!$user->hasPermission($permission)) ? false : true;
        // return $check;
        return auth()->user()->hasPermission($permission);
    }
}

?>