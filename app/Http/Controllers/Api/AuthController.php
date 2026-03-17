<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use Google_Client;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{




    public function login(Request $request)
    {

        $mobile = $request->input('mobile');
        $token_id = $request->input('token_id');

        if ($mobile) {

            $user = User::where('mobile', $mobile)->first();

            if (!$user) {
                return response()->json(['status' => 'User not found'], 400);
            } else {

                $otp =  1234;

                $updateArray =  array(
                    'otp' =>  $otp,
                    'token_id' =>  $token_id,
                );

                User::where('id', $user->id)->update($updateArray);

                $otp = rand(1000, 9999);

                $message = "Your NexOcart verification code is $otp. Do not share this OTP with anyone.";

                $this->sendNotification($user->id, 'NexOcart OTP Verification', $message);


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
        $token_id = $request->input('token_id');


        if ($name != '' &&  $email != '' &&  $mobile) {

            $user = User::where('mobile', $mobile)->first();

            $check_referral = Referral::where('referral_code', $referral_code)->first();


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

                if (!empty($referral_code)) {

                    $check_referral = Referral::where('referral_code', $referral_code)->first();

                    if (!$check_referral) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid referral code'
                        ], 400);
                    }
                }
            }

            $insertArray = array(
                'name'          => $name,
                'email'         => $email,
                'mobile'        => $mobile,
                'otp'           => '1234',
                'referral_code' => $referral_code,
                'auth_level'    => 3,
                'token_id'      => $token_id,
                'created_at'    => now()

            );

            $user = User::create($insertArray);

            // Send push notification to the newly registered user
            if (!empty($device_id)) {
                try {
                    sendPushNotificationToUser($device_id, 'Welcome to NexOCart!', 'You have registered successfully. Start exploring now!');
                } catch (\Exception $e) {
                    // Log the error but don't fail the registration
                    \Log::error('Push notification failed for user ' . $user->id . ': ' . $e->getMessage());
                }
            }

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
                $user_data    = User::where('id',  $user_id)->first();

                $success_array = array('status' => 'success', 'message' => 'Otp verified successfully', 'data' => $user_data);
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


    public function sendNotification($userid, $title, $msg)
    {
        $firebaseToken = User::Where('id', $userid)->first('token_id');

        $NotificationData = ['title' => $title, 'body'  => $msg];
        $titles           = ['title' => $title, 'body'  => $msg];
        $data             = [
            'message' => [
                'token' => $firebaseToken['token_id'],
                'notification' => $titles,
                'data' => $NotificationData
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/nexocart-3f870/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            return response()->json(['error' => $responseData['error']], 500);
        }
        return response()->json(['response' => $responseData]);
    }

    public function getAccessToken()
    {
        $credentialsPath = storage_path('app/firebase-service-account.json');
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
}
