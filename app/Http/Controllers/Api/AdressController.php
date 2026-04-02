<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\PinCode;

use Illuminate\Http\Request;

class AdressController extends Controller
{

    public function getAllAddress(Request $request)
    {

        $user_id    = $request->input('user_id');
        $address   = Address::where('user_id', $user_id)->get();

        if ($address->isNotEmpty()) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $address);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }



    public function addAddress(Request $request)
    {


        $user_id    = $request->input('user_id');
        $type       = $request->input('type');
        $name       = $request->input('name');
        $mobile     = $request->input('mobile');
        $address    = $request->input('address');
        $landmark   = $request->input('landmark');
        $pincode    = $request->input('pincode');

        if ($user_id != '' && $name != '' && $mobile != '' && $address != '' && $pincode != '') {

            $checkPincodeExistence = PinCode::where('pincode', $pincode)->where('status', 1)->exists();

            $addressExists = Address::where('user_id', $user_id)->exists();

            if (!$checkPincodeExistence) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Delivery is not available for this pincode '
                ], 400);
            }


            $insertArray = array(
                'user_id'       =>  $user_id,
                'type'          =>  $type,
                'name'          =>  $name,
                'mobile'        =>  $mobile,
                'address'       =>  $address,
                'landmark'      =>  $landmark,
                'pincode'       =>  $pincode,
                'is_default'    =>  $addressExists ? 0 : 1
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
        $type       = $request->input('type');
        $name       = $request->input('name');
        $mobile     = $request->input('mobile');
        $address    = $request->input('address');
        $landmark   = $request->input('landmark');
        $pincode    = $request->input('pincode');

        if ($id != '' && $name != '' && $mobile != '' && $address != '' && $pincode != '') {


            $checkPincodeExistence = PinCode::where('pincode', $pincode)->where('status', 1)->exists();

            if (!$checkPincodeExistence) {
                $error_array = array('status' => 'error', 'message' => 'Delivery is not available for this pincode');
                return response()->json(array($error_array), 400);
            }

            $updateArray = array(
                'name'          =>  $name,
                'type'          =>  $type,
                'mobile'        =>  $mobile,
                'address'       =>  $address,
                'landmark'      =>  $landmark,
                'pincode'       =>  $pincode,
            );

            $address = Address::where('id', $id)->update($updateArray);

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



    public function deleteAddress(Request $request)
    {
        $address_id = $request->input('address_id');

        if (!$address_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address ID is required'
            ], 400);
        }

        $address = Address::find($address_id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found'
            ], 404);
        }

        if ($address->is_default == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Default address cannot be deleted'
            ], 400);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully'
        ], 200);
    }




    public function setDefaultAddress(Request $request)
    {
        $address_id = $request->input('address_id');
        $user_id = $request->input('user_id');

        if (!$address_id || !$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address ID and User ID are required'
            ], 400);
        }

        Address::where('user_id', $user_id)->update([
            'is_default' => 0
        ]);

        $address = Address::where('id', $address_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found'
            ], 404);
        }

        // Set selected address as default
        $address->update([
            'is_default' => 1
        ]);

        $success_array = array('status' => 'success', 'message' => 'Default address updated successfully');
        return response()->json(array($success_array), 200);
    }
}
