<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\DirectOrder;
use App\Models\DeliveryPerson;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard()
    {
        
        $shop_count             = count(Shop::where('status',1)->get());
        $customer_count         = count(User::where('auth_level',3)->where('status',1)->get());
        $order_count            = count(Order::get());
        $direct_order_count     = count(DirectOrder::get());
        $delivert_person_count  = count(DeliveryPerson::where('status',1)->get());

        return view('backend.dashboard',compact('shop_count','customer_count','order_count','delivert_person_count','direct_order_count','delivert_person_count'));
    }


}
