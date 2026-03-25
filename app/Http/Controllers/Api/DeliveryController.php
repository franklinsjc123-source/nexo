<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryPerson;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class DeliveryController extends Controller
{


    public function deliveryLogin(Request $request)
    {
        $mobile = $request->mobile;
        $password = $request->password;
        $token_id = $request->token_id;

        $delivery = DeliveryPerson::where('mobile', $mobile)->first();

        if (!$delivery) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile number not found'
            ], 400);
        }

        if (!Hash::check($password, $delivery->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password'
            ], 400);
        }

        if ($delivery->status != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Account inactive'
            ], 400);
        }

        $updateArray =  array(
            'device_id' =>  $token_id,
        );

        DeliveryPerson::where('id', $delivery->id)->update($updateArray);



        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $delivery->id,
                'name' => $delivery->name,
                'mobile' => $delivery->mobile,
                'email' => $delivery->email,
            ]
        ]);
    }



    public function getAllNewOrders(Request $request)
    {
        $deliver_person_id = $request->deliver_person_id;

        $orders = Order::whereIn('deliver_person_id', [0, $deliver_person_id])
            ->with('items.shopData')
            ->select(
                'id',
                'order_id',
                'amount',
                'ship_amount',
                'order_status',
                'payment_type',
                'created_at'
            )
            ->orderBy('id', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Records not found'
            ], 400);
        }

        // ✅ LOOP ALL ORDERS
        $data = $orders->map(function ($order) {

              $total_product_count = collect($order->items)
            ->groupBy('product_id')
            ->count();

            $shopNames = collect($order->items)
                ->pluck('shopData.shop_name')
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');

            return [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'shop_name' => $shopNames,
                'total_quantity' => $total_product_count,
                'amount' => (float)$order->amount + (float)$order->ship_amount,
                'order_status' => $order->order_status,
                'payment_type' => $order->payment_type,
                'image_url' => '',
                'order_type' => 'cart_order',
                'date' => date('d-m-Y', strtotime($order->created_at)),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function takenOrder(Request $request)
    {
        $deliver_person_id = $request->deliver_person_id;
        $order_id = $request->order_id;

        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 400);
        }

        if ($order->deliver_person_id == 0) {

            $order->deliver_person_id = $deliver_person_id;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order taken successfully'
            ], 200);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Order already taken'
            ], 400);
        }
    }




    public function getDeliveryHistory(Request $request)
    {

        $deliver_person_id = $request->deliver_person_id;

        $orders = Order::where('deliver_person_id', $deliver_person_id)->get();

        if ($orders->isNotEmpty()) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $orders);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }
}
