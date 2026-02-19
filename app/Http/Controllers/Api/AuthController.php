<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(Request $request)
    {

        $mobile = $request->input('mobile');

        if ($mobile) {

            $user = User::where('mobile', $mobile)->first();

            if (!$user) {
                return response()->json(['status' => 'User not found'], 400);
            } else {

                $otp =  1234;

                $updateArray =  array(
                    'otp' =>  $otp
                );

                User::where('id', $user->id)->update($updateArray);


                //         $url = "https://2factor.in/API/V1/0db8d8b3-0825-11f1-a6b2-0200cd936042/SMS/+91$mobile/$otp/OTP1";
                //         $curl = curl_init();
                //         curl_setopt($curl, CURLOPT_URL, $url);
                //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                //         curl_setopt($curl, CURLOPT_HEADER, false);
                //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                //         $data = curl_exec($curl);
                //         curl_close($curl);


                $success_array = array('status' => 'success', 'message' => 'OTP send successfully', 'otp' => '1234');
                return response()->json(array($success_array), 200);

            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }



    public function register(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $referral_code = $request->input('referral_code');

        if ($name != '' &&  $email != '' &&  $mobile) {

            $user = User::where('mobile', $mobile)->first();

            if ($user) {

                if ($user->email ==  $email) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Email  already exists'
                    ], 400);
                }

                if ($user->mobile ==  $mobile) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Mobile number already exists'
                    ], 400);
                }
            }

            $insertArray = array(
                'name'          => $name,
                'email'         => $email,
                'mobile'        => $mobile,
                'referral_code' => $referral_code,
                'auth_level'    => 3,
                'created_at'    => now()

            );

            $user = User::create($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Register Successfully', 'user_id' => $user->id);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function checkOTP(Request $request)
    {

        $mobile = $request->input('mobile');
        $otp = $request->input('otp');

        if ($mobile != '' && $otp != '') {

            $user_id    = User::where('mobile', $mobile)->value('id');
            $stored_otp = User::where('id', $user_id)->value('otp');

            if ($stored_otp ==  $otp) {

                $updateArray =  array(
                    'is_verified' => 1
                );
                User::where('id', $user_id)->update($updateArray);
                $user_data    = User::where('id',  $user_id )->first();

                $success_array = array('status' => 'success', 'message' => 'Otp verified successfully','data' => $user_data);
                return response()->json(array($success_array), 200);

            } else {
                $error_array = array('status' => 'error', 'message' => 'Otp not matched');
                return response()->json(array($error_array), 400);
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function getUserDetails(Request $request)
    {

        $user_id = $request->input('user_id');

        if ($user_id) {

            $user = User::from('users as u')->where('u.id', $user_id)->first();

            if ($user) {
                $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $user);
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'success', 'message' => 'Something went wrong');
                return response()->json(array($error_array), 400);
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function updateProfile(Request $request)
    {

        $user_id = $request->input('user_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        if ($name != '' &&  $email != '' &&  $mobile != '') {

            $updateArray = array(
                'name'          => $name,
                'email'         => $email,
                'mobile'        => $mobile,
            );

            $update = User::where('id', $user_id)->update($updateArray);

            if ($update) {

                $success_array = array('status' => 'success', 'message' => 'Updated Successfully');
                return response()->json(array($success_array), 200);

            } else {

                $error_array = array('status' => 'error', 'message' => 'Not updated');
                return response()->json(array($error_array), 400);

            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }

}
