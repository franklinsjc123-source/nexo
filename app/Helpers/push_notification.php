<?php

use App\Models\User;
if (!function_exists('sendRequestNotification')) {

    function sendRequestNotification($title = '', $msg = '')
    {
        $deviceTokens = User::where('auth_level', '!=', '1')
                            ->whereNotNull('device_id')
                            ->pluck('device_id')
                            ->toArray();

        if (empty($deviceTokens)) {
            return ['error' => 'No device tokens found'];
        }
        $results = [];
        foreach ($deviceTokens as $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    // 'notification' => [
                    //     'title' => $title,
                    //     'body'  => $msg
                    // ],
                    'data' => [
                        'title'    => $title,
                        'message'  => $msg
                    ],
                     'android' => [
                         "priority" => "HIGH",                       
                    ]
                ]
            ];

            $headers = [
                'Authorization: Bearer ' . getAccessToken(),
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/findflicker-bec16/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            curl_close($ch);

            $results[] = json_decode($response, true);
        }

        return $results;
    }
}


function getAccessToken()
{
    $credentialsPath = storage_path('app/firebase-service-account.json');
    $client = new Google_Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}
