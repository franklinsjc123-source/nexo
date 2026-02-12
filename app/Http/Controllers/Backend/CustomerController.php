<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;


use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class CustomerController extends Controller
{
    use PermissionCheckTrait;

    public function customers()
    {
         if (!$this->checkPermission('Customers')) {
            return view('unauthorized');
        }
        
        $records   =  User::where('auth_level',3)->orderBy('id', 'ASC')->get();
        return view('backend.customers.list', compact('records'));

    }


}
