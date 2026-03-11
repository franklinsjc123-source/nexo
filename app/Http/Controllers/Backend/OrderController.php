<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use PermissionCheckTrait;

    public function orders()
    {
        if (!$this->checkPermission('Orders')) {
            return view('unauthorized');
        }

        $auth_level =  Auth::user()->auth_level;

        if ($auth_level == 4) {

            $shop_id = Shop::where('user_id', auth()->id())->value('id');

            $records = Order::whereHas('items', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })
                ->orderBy('id', 'DESC')
                ->get();

                
        } else {
            $records   =  Order::orderBy('id', 'DESC')->get();
        }



        return view('backend.order.list', compact('records'));
    }



    public function updateOrderStatus(Request $request)
    {
        Order::where('id', $request->order_id)
            ->update(['order_status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully'
        ]);
    }
}
