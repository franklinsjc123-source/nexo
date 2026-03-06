<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;

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
