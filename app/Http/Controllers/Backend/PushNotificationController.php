<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\PermissionCheckTrait;
use Google_Client;

class PushNotificationController extends Controller
{
    use PermissionCheckTrait;

    public function index()
    {
        // For now, allow auth_level 1 and 2 (admins)
        if (!in_array(Auth::user()->auth_level, [1, 2])) {
             return view('unauthorized');
        }

        return view('backend.push_notification.index');
    }

    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'offer_message' => 'required',
            // 'offer_image'   => 'required|image'
        ]);

        $offer_message = $request->offer_message ?? '';
        $offer_image   = '';

        if ($request->hasFile('offer_image')) {
            $imageName = time() . '.' . $request->offer_image->extension();
            $request->offer_image->move(public_path('uploads/notifications'), $imageName);
            $offer_image = $imageName;
        }

        // Send to all customers (auth_level 3)
        $customers = User::where('auth_level', 3)->get();
        $title     = "New Notification - NexOcart";
        $imageUrl  = $offer_image ? asset('uploads/notifications/' . $offer_image) : null;

        $sentCount = 0;
        foreach ($customers as $c) {
            if (!empty($c->token_id)) {
                $this->sendNotification($c->id, $title, $offer_message, $imageUrl);
                $sentCount++;
            }
        }

        return redirect()->back()->with('success', "Push Notification Sent Successfully to $sentCount customers.");
    }

    public function sendNotification($userid, $title, $msg, $imageUrl = null)
    {
        $user = User::where('id', $userid)->first();

        if (!$user || empty($user->token_id)) {
            return response()->json(['error' => 'No token found']);
        }

        // Replace HTML line breaks with newline
        $msg = str_replace(['<br>', '<br/>', '<br />'], "\n", $msg);

        $notification = [
            'title' => $title,
            'body'  => substr($msg, 0, 100), // short preview
        ];

        if ($imageUrl) {
            $notification['image'] = $imageUrl;
        }

        $data = [
            'full_message' => $msg
        ];

        $payload = [
            'message' => [
                'token' => $user->token_id,

                'notification' => $notification,
                'data' => $data,

                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',

                        // ✅ THIS IS THE MAIN FIX
                        'default_vibrate_timings' => true,
                        'default_sound' => true,

                        // BIG TEXT SUPPORT
                        'body' => $msg, // full message
                    ]
                ]
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/nexocart-3f870/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);

        return response()->json(['response' => json_decode($response, true)]);
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
