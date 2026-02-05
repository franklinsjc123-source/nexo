<?php

namespace App\Http\Traits;
use App\Models\ActivityLog;
use App\Models\LogModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait ActivityLogTrait
{
    public function storeLog($content)
    {
        $user_id  = Auth::user()->id;       
        $userData = User::from('users as u')
                        ->leftJoin('roles as r','r.id','=','u.role_id')
                        ->first(['u.name','r.display_name as role']);
                        
        $name       = isset($userData->name) ? $userData->name:'';
        $role_name  = isset($userData->role) ? $userData->role:'';        
        $msg =  $content." ".$name." ( ".$role_name." )";
        $logArray = array(
            "created_by"  => $user_id,
            "message"     => $msg
        );
        $log = LogModel::create($logArray);
    }
}


?>