<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Offers;
use App\Models\OffersUsed;
use Carbon\Carbon;

use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function getAllOffers(Request $request)
    {

        $offers = Offers::where('status', 1)->whereDate('expiry_date', '>=', Carbon::today())->get();


        if ($offers->isNotEmpty()) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $offers);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }


    public function applyOffer(Request $request)
    {
        $user_id  = $request->input('user_id');
        $cart_id  = $request->input('cart_id');
        $offer_ids = $request->input('offer_id');

        if ($user_id && $offer_ids) {

         OffersUsed::where('cart_id', $cart_id)
            ->where('user_id', $user_id)
            ->delete();

            $offer_ids = explode(',', $offer_ids);

            foreach ($offer_ids as $offer_id) {

                $offer = Offers::find($offer_id);

                if (!$offer) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Offer not found for ID ' . $offer_id
                    ], 400);
                }

                $cart = Cart::find($cart_id);

                if (!$cart) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cart not found'
                    ], 400);
                }

                $shop_total = CartItems::where('cart_id', $cart_id)
                    ->where('shop_id', $offer->shop_id)
                    ->sum('total_price');

                if ($shop_total < $offer->minimum_order_amount) {

                    $shop_name = Shop::where('id', $offer->shop_id)->value('shop_name');

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Minimum order amount ₹' . $offer->minimum_order_amount . ' required for this offer from ' . $shop_name
                    ], 400);
                }

                // ✅ Insert or update automatically
                OffersUsed::updateOrCreate(
                    [
                        'user_id' => $user_id,
                        'cart_id' => $cart_id,
                        'offer_id' => $offer_id
                    ],
                    [
                        'updated_at' => now()
                    ]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Offers applied successfully'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Parameters Missing'
        ], 400);
    }



    public function removeOffer(Request $request)
    {
        $user_id  = $request->input('user_id');
        $cart_id  = $request->input('cart_id');
        $offer_id = $request->input('offer_id');

        if (!$user_id || !$cart_id || !$offer_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Required parameters missing'
            ], 400);
        }

        $deleted = OffersUsed::where('user_id', $user_id)
            ->where('offer_id', $offer_id)
            ->where('cart_id', $cart_id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Offer removed successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found'
            ], 404);
        }
    }
}
