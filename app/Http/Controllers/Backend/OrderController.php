<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DirectOrder;
use App\Models\User;


use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class OrderController extends Controller
{
    use PermissionCheckTrait;

    public function orders()
    {
         if (!$this->checkPermission('Orders')) {
            return view('unauthorized');
        }

        $records   =  Order::orderBy('id', 'DESC')->get();
        return view('backend.order.list', compact('records'));

    }


      public function directOrders()
    {
         if (!$this->checkPermission('Orders')) {
            return view('unauthorized');
        }

        $records   =  DirectOrder::orderBy('id', 'DESC')->get();
        return view('backend.order.direct_order_list', compact('records'));

    }


    public function updateOrderStatus(Request $request)
{
    DirectOrder::where('id', $request->order_id)
        ->update(['status' => $request->status]);

    return response()->json([
        'status' => true,
        'message' => 'Order status updated successfully'
    ]);
}





}
