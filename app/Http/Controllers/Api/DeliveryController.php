<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PinCode;

use Illuminate\Http\Request;

class DeliveryController extends Controller
{

    public function getAllNewOrders()
    {

        $orders = Order::where('deliver_person_id', 0)->whereDate('created_at', today())->get();

        if ($orders->isNotEmpty()) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $orders);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }
}
