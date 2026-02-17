<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;

use Illuminate\Http\Request;

class AdressController extends Controller
{

    public function addAddress(Request $request)
    {


        $user_id    = $request->input('user_id');
        $name       = $request->input('name');
        $mobile     = $request->input('mobile');
        $address    = $request->input('address');
        $pincode    = $request->input('pincode');

        if ($user_id != '' && $name != '' && $mobile != '' && $address != '' && $pincode != '') {


            $insertArray = array(
                'user_id'       =>  $user_id,
                'name'          =>  $name,
                'mobile'        =>  $mobile,
                'address'       =>  $address,
                'pincode'       =>  $pincode,
            );

            $address = Address::create($insertArray);

            if ($address) {
                $success_array = array('status' => 'success', 'message' => 'Address added successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Address not added');
                return response()->json(array($error_array), 400);
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }




    public function editAddress(Request $request)
    {

        $id         = $request->input('id');
        $name       = $request->input('name');
        $mobile     = $request->input('mobile');
        $address    = $request->input('address');
        $pincode    = $request->input('pincode');

        if ($id != '' && $name != '' && $mobile != '' && $address != '' && $pincode != '') {


            $updateArray = array(
                'name'          =>  $name,
                'mobile'        =>  $mobile,
                'address'       =>  $address,
                'pincode'       =>  $pincode,
            );

            $address = Address::where('id',$id)->update($updateArray);

            if ($address) {
                $success_array = array('status' => 'success', 'message' => 'Address updated successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Address not updated');
                return response()->json(array($error_array), 400);
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }
}
