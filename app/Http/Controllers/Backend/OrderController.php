<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Shop;
use App\Models\Invoice;
use App\Models\DeliveryPerson;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

            $records = Order::with(['customerData', 'deliveryPerson'])->select(
                'orders.*',
                'shop_invoice.invoice_path', 'shop_invoice.final_shop_total','shop_invoice.is_dispatched',
                DB::raw('SUM(order_items.price) as shop_total')
            )
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->leftJoin('shop_invoice', function ($join) use ($shop_id) {
                    $join->on('shop_invoice.order_id', '=', 'orders.id')
                        ->where('shop_invoice.shop_id', '=', $shop_id);
                })
                ->where('order_items.shop_id', $shop_id)
                ->groupBy('orders.id', 'shop_invoice.invoice_path')
                ->orderBy('orders.id', 'DESC')
                ->get();
        } else {
            $records   =  Order::with(['customerData', 'deliveryPerson'])->orderBy('id', 'DESC')->get();
        }

        $deliveryPersons = DeliveryPerson::orderBy('name', 'ASC')->get();

        return view('backend.order.list', compact('records', 'deliveryPersons'));
    }



    public function updateOrderStatus(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }

        if (Auth::user()->auth_level == 4) {
            if ($order->order_status == 2 || $order->order_status == 3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot update status of a delivered or cancelled order.'
                ]);
            }

            $shop_id = Shop::where('user_id', Auth::user()->id)->value('id');
            $now = Carbon::now('Asia/Kolkata');
            $status = $request->status;

            if ($status == 2) {
                $order->order_status = 2;
                $order->delivery_date = $now;
            } elseif ($status == 3) {
                $order->order_status = 3;
                $order->cancel_date = $now;
            } elseif ($status == 4) {
                Invoice::where('order_id', $order->id)
                    ->where('shop_id', $shop_id)
                    ->update(['is_dispatched' => 1]);

                $total_shops = Invoice::where('order_id', $order->id)->count();

                $dispatched_shops = Invoice::where('order_id', $order->id)
                    ->where('is_dispatched', 1)
                    ->count();

                if ($total_shops == $dispatched_shops) {
                    $order->order_status = 4;
                    $order->shipped_date = $now;
                }
            }

            $order->save();
        } else {
            $order->update(['order_status' => $request->status]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully'
        ]);
    }

    public function getOrderItems(Request $request)
    {
        $order_id = $request->order_id;

        $auth_level = Auth::user()->auth_level;

        $query = OrderItems::with(['product', 'unitData', 'shopData'])
            ->where('order_id', $order_id);

        if ($auth_level == 4) {
            $shop_id = Shop::where('user_id', auth()->id())->value('id');
            $query->where('shop_id', $shop_id);
        }

        $items = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $items
        ]);
    }

    public function assignDeliveryPerson(Request $request)
    {
        if (!in_array(Auth::user()->auth_level, [1, 2])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Order not found']);
        }

        $order->deliver_person_id = $request->delivery_person_id;
        $order->save();

        return response()->json(['status' => true, 'message' => 'Delivery person assigned successfully']);
    }
}
