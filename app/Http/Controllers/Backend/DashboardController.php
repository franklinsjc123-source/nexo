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

    public function account_deletion()
    {
        return view('account-deletion');
    }

    public function post_account_deletion(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:10',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Your account and associated data have been permanently deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'No account found with the provided mobile number.');
        }
    }

    public function checkNewOrders(Request $request)
    {
        // Only allow auth_level 1 (Super Admin) and 2 (presumably Admin/Manager)
        if (!in_array(Auth::user()->auth_level, [1, 2])) {
            return response()->json(['status' => 'no_access']);
        }

        $last_direct_id = $request->input('last_direct_id', 0);
        $last_order_id = $request->input('last_order_id', 0);
        
        $direct_query = DirectOrder::query();
        $order_query = Order::query();

        if ($last_direct_id == -1 || $last_order_id == -1) {
            return response()->json([
                'status' => 'init', 
                'latest_direct_id' => $direct_query->max('id') ?? 0,
                'latest_order_id' => $order_query->max('id') ?? 0
            ]);
        }

        $new_direct = $direct_query->where('id', '>', $last_direct_id)->get();
        $new_orders = $order_query->where('id', '>', $last_order_id)->get();

        if ($new_direct->count() > 0 || $new_orders->count() > 0) {
            return response()->json([
                'status' => 'new', 
                'latest_direct_id' => $new_direct->max('id') ?? $last_direct_id,
                'latest_order_id' => $new_orders->max('id') ?? $last_order_id,
                'direct_count' => $new_direct->count(),
                'order_count' => $new_orders->count()
            ]);
        }
        return response()->json(['status' => 'no_new']);
    }
}
