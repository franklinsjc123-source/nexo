<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Offers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;
use Google_Client;


class OfferController extends Controller
{
    use PermissionCheckTrait;

    public function offers()
    {
        if (!$this->checkPermission('Offers')) {
            return view('unauthorized');
        }


        if (Auth::user()->auth_level == 4) {

            $user_id   =   auth()->id();
            $shop_id   =  Shop::where('user_id', $user_id)->value('id');
            $records   =  Offers::where('shop_id', $shop_id)->orderBy('id', 'desc')->get();
        } else {

            $records   =  Offers::orderBy('id', 'desc')->get();
        }


        return view('backend.offers.list', compact('records'));
    }

    public function addOffer($id = '')
    {
        $records = '';
        if ($id > 0) {
            $records   =  Offers::where('id', $id)->first();
        }

        $shopData       =  Shop::orderBy('shop_name', 'ASC')->get();

        return view('backend.offers.add_edit', compact('records', 'id', 'shopData'));
    }

    public function storeUpdateOffer(Request $request)
    {



        $id                     = $request->id ?? 0;
        $shop_id                = $request->shop_id ?? '';
        $offer_code             = $request->offer_code ?? '';
        $expiry_date            = $request->expiry_date ?? '';
        $discount_percentage    = $request->discount_percentage ?? '';
        $minimum_order_amount   = $request->minimum_order_amount ?? '';
        $data = [
            'shop_id'               => $shop_id,
            'offer_code'            => $offer_code,
            'expiry_date'           => $expiry_date,
            'discount_percentage'   => $discount_percentage,
            'minimum_order_amount'  => $minimum_order_amount,
        ];


        if (empty($id)) {

            $insert = Offers::create($data);

            $customers   =  User::where('auth_level', 3)->get();


            $shopName = Shop::where('id', $shop_id)->value('shop_name');
            $title    = ($shopName ?? 'Shop') . " - New Offer!";

            $message = "🎉 EXCLUSIVE OFFER! 🎉\n\n";
            $message .= "🎁 Get {$discount_percentage}% OFF on your purchase!\n";
            $message .= "🎟️ Coupon code: {$offer_code}\n";
            $message .= "🛒 Minimum Order Amount: ₹{$minimum_order_amount}\n";
            $message .= "⏳ Valid upto: {$expiry_date}\n\n";
            $message .= "🏃‍♂️ Shop now to grab this amazing deal!";

            if ($customers->count() > 0) {
                $this->sendBulkNotifications($customers, $title, $message);
            }

            return redirect()
                ->route('offers')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Offer Saved Successfully' : 'Something went wrong!'
                );
        }

        Offers::where('id', $id)->update($data);

        return redirect()->route('offers')->with('success', 'Offer Updated Successfully');
    }





    public function sendNotification($userid, $title, $msg)
    {

        $firebaseToken = User::where('id', $userid)->first('token_id');


        $NotificationData = ['title' => $title, 'body'  => $msg, 'shop_id' => (string)$userid];
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

    public function sendBulkNotifications($customers, $title, $msg)
    {
        $accessToken = $this->getAccessToken();
        $url = 'https://fcm.googleapis.com/v1/projects/nexocart-3f870/messages:send';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $chunks = $customers->chunk(50);

        foreach ($chunks as $chunk) {
            $mh = curl_multi_init();
            $handles = [];

            foreach ($chunk as $c) {
                if (empty($c->token_id)) {
                    continue;
                }

                $NotificationData = ['title' => $title, 'body'  => $msg, 'shop_id' => (string)$c->id];
                $titles           = ['title' => $title, 'body'  => $msg];
                $data             = [
                    'message' => [
                        'token' => $c->token_id,
                        'notification' => $titles,
                        'data' => $NotificationData
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                curl_multi_add_handle($mh, $ch);
                $handles[] = $ch;
            }

            if (empty($handles)) {
                curl_multi_close($mh);
                continue;
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh);
            } while ($running > 0);

            foreach ($handles as $ch) {
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);
        }
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
