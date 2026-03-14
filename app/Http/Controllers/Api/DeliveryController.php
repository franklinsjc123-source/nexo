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

        $delivery = DeliveryPerson::where('mobile', $mobile)->first();

        if (!$delivery) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile number not found'
            ]);
        }

        if (!Hash::check($password, $delivery->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password'
            ]);
        }

        if ($delivery->status != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Account inactive'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $delivery->id,
                'name' => $delivery->name,
                'mobile' => $delivery->mobile,
                'email' => $delivery->email
            ]
        ]);
    }



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
