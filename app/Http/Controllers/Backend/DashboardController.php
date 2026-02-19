<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\DirectOrder;
use App\Models\DeliveryPerson;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard()
    {

        if (Auth::user()->auth_level == 4) {

            $user_id   =   auth()->id();
            $shop_id   =  Shop::where('user_id', $user_id)->orderBy('shop_name', 'ASC')->get();

            $shop_count                 = 0;
            $customer_count             = 0;
            $order_count                = count(Order::get());
            $today_order_count          = count(Order::get());
            $direct_order_count         = count(DirectOrder::get());
            $today_direct_order_count   = count(DirectOrder::get());
            $delivert_person_count      = 0;

        } else {

            $shop_count                 = count(Shop::where('status', 1)->get());
            $customer_count             = count(User::where('auth_level', 3)->where('status', 1)->get());
            $order_count                = count(Order::get());
            $today_order_count          = count(Order::get());
            $direct_order_count         = count(DirectOrder::get());
            $today_direct_order_count   = count(DirectOrder::get());
            $delivert_person_count      = count(DeliveryPerson::where('status', 1)->get());
        }


        return view('backend.dashboard', compact('shop_count', 'customer_count', 'order_count', 'delivert_person_count', 'direct_order_count', 'delivert_person_count'));
    }
}
