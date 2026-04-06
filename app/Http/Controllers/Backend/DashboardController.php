<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\DirectOrder;
use App\Models\DeliveryPerson;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {

        if (Auth::user()->auth_level == 4) {

            $user_id   =   auth()->id();
            $shop_id   =  Shop::where('user_id', $user_id)->value('id');

            $shop_count                 = 0;
            $customer_count             = 0;
            $order_count                = $order_count = OrderItems::where('shop_id', $shop_id)->distinct('order_id')->count('order_id');
            $product_count              = count(Product::where('shop', $shop_id)->get());
            $today_order_count          = OrderItems::join('orders', 'orders.id', '=', 'order_items.order_id')->where('order_items.shop_id', $shop_id)->whereDate('orders.created_at', Carbon::today())->distinct('order_items.order_id')->count('order_items.order_id');;
            $direct_order_count         = count(DirectOrder::where('shop_id', $shop_id)->get());
            $today_direct_order_count   = DirectOrder::where('shop_id', $shop_id)->whereDate('created_at', Carbon::today())->count();
            $delivert_person_count      = 0;
        } else {

            $shop_count                 = count(Shop::where('status', 1)->get());
            $customer_count             = count(User::where('auth_level', 3)->where('status', 1)->get());
            $order_count                = count(Order::get());
            $product_count              = count(Product::get());
            $today_order_count          = Order::whereDate('created_at',  Carbon::today())->count();
            $direct_order_count         = count(DirectOrder::get());
            $today_direct_order_count   = DirectOrder::whereDate('created_at',  Carbon::today())->count();
            $delivert_person_count      = count(DeliveryPerson::where('status', 1)->get());
        }

        $categoryData = DB::table('category')
            ->leftJoin('shop', function ($join) {
                $join->whereRaw("FIND_IN_SET(category.id, shop.category)");
            })
            ->leftJoin('products', 'products.category', '=', 'category.id')
            ->select(
                'category.id',
                'category.category_name',
                DB::raw('COUNT(DISTINCT shop.id) as shop_count'),
                DB::raw('COUNT(DISTINCT products.id) as product_count')
            )
            ->groupBy('category.id', 'category.category_name')
            ->get();

        $categoryLabels = $categoryData->pluck('category_name');
        $shopCounts     = $categoryData->pluck('shop_count');
        $productCounts  = $categoryData->pluck('product_count');
        $categoryLabels = [];
        $shopCounts = [];
        $productCounts = [];

        foreach ($categoryData as $row) {
            $categoryLabels[] = $row->category_name;
            $shopCounts[] = $row->shop_count;
            $productCounts[] = $row->product_count;
        }


        return view('backend.dashboard', compact('shop_count', 'customer_count', 'order_count', 'delivert_person_count', 'direct_order_count', 'delivert_person_count', 'today_direct_order_count', 'today_order_count', 'categoryLabels', 'shopCounts', 'productCounts', 'product_count'));
    }

    public function privacy_policy()
    {
        return view('privacy-policy');
    }
}
