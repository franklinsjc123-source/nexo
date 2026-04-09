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
        $offer_message          = $request->offer_message ?? '';
        $offer_image            = '';

        if ($request->hasFile('offer_image')) {
            $imageName = time() . '.' . $request->offer_image->extension();
            $request->offer_image->move(public_path('uploads/offers'), $imageName);
            $offer_image = $imageName;
        }

        $data = [
            'shop_id'               => $shop_id,
            'offer_code'            => $offer_code,
            'expiry_date'           => $expiry_date,
            'discount_percentage'   => $discount_percentage,
            'minimum_order_amount'  => $minimum_order_amount,
            'offer_message'         => $offer_message,
        ];

        if ($offer_image) {
            $data['offer_image'] = $offer_image;
        }

        if (empty($id)) {

            $insert = Offers::create($data);

            $customers   =  User::where('auth_level', 3)->get();


            $shopName = Shop::where('id', $shop_id)->value('shop_name');
            $title    = ($shopName ?? 'Shop') . " - New Offer!";

            $message = "🎁 Get " . $discount_percentage . "% OFF! Use code: " . $offer_code . "\n";
            $message .= "Min. Order: ₹" . $minimum_order_amount . " | Valid till: " . $expiry_date . "\n";
            $message .= "--------------------------\n";
            $message .= $offer_message;

            $imageUrl = $offer_image ? asset('uploads/offers/' . $offer_image) : null;

            foreach ($customers as  $c) {
                if (!empty($c->token_id)) {
                    $this->sendNotification($c->id, $title, $message, $imageUrl);
                }
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
