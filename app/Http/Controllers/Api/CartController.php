<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\Offers;
use App\Models\OffersUsed;
use App\Models\Address;

use App\Models\ProductAttributes;
use Carbon\Carbon;


use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $product_id = $request->product_id;
        $user_id    = $request->user_id;
        $unit       = $request->unit;
        $quantity   = $request->quantity ?? 1;

        if (!$product_id || !$user_id || !$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Parameters Missing'
            ], 400);
        }

        $product = Product::find($product_id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found'
            ], 404);
        }

        // ✅ correct shop column
        $shop_id = $product->shop;

        $attribute = ProductAttributes::where('product_id', $product_id)
            ->where('unit', $unit)
            ->first();

        if (!$attribute) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit not found'
            ], 404);
        }

        $price = $attribute->discount_price;
        $discount_price = $attribute->original_price ?? 0;

        $cart = Cart::firstOrCreate(
            ['user_id' => $user_id],
            ['total_amount' => 0]
        );

        $item = CartItems::where('cart_id', $cart->id)
            ->where('product_id', $product_id)
            ->where('unit', $unit)
            ->first();

        if ($item) {

            $item->quantity = $quantity;
            $item->total_price = $quantity * $item->price;
            $item->save();
        } else {

            CartItems::create([
                'cart_id'        => $cart->id,
                'product_id'     => $product_id,
                'shop_id'        => $shop_id, // ✅ correct value
                'unit'           => $unit,
                'quantity'       => $quantity,
                'price'          => $price,
                'discount_price' => $discount_price,
                'total_price'    => $price * $quantity
            ]);
        }

        $cart->total_amount = CartItems::where('cart_id', $cart->id)->sum('total_price');
        $cart->save();

        $cart_count = CartItems::where('cart_id', $cart->id)->count();

        return response()->json([
            'status'      => true,
            'message'     => 'Item added to cart successfully',
            'cart_total'  => $cart->total_amount,
            'cart_count'  => $cart_count
        ]);
    }

    public function viewCart(Request $request)
    {
        $user_id = $request->input('user_id');

        if ($user_id != '') {

            $cart = Cart::with(['items.product:id,product_name,product_image'])
                ->where('user_id', $user_id)
                ->first();

            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart not found'
                ], 404);
            }

            // ==============================
            // Offers List with is_used flag
            // ==============================
            $offers = Offers::where('status', 1)
                ->whereDate('expiry_date', '>=', Carbon::today())
                ->get()
                ->map(function ($offer) use ($cart, $user_id) {

                    $is_used = OffersUsed::where('offer_id', $offer->id)
                        ->where('cart_id', $cart->id)
                        ->where('user_id', $user_id)
                        ->exists();

                    return [
                        'id' => $offer->id,
                        'shop_id' => $offer->shop_id,
                        'offer_code' => $offer->offer_code,
                        'discount_percentage' => $offer->discount_percentage,
                        'minimum_order_amount' => $offer->minimum_order_amount,
                        'expiry_date' => $offer->expiry_date,
                        'is_used' => $is_used ? 1 : 0
                    ];
                });


            // ==============================
            // Delivery Address
            // ==============================
            $delivery_address = Address::where('user_id', $user_id)
                ->where('is_default', 1)
                ->first();


            // ==============================
            // Item Price
            // ==============================
            $item_price = CartItems::where('cart_id', $cart->id)
                ->sum('total_price');


            // ==============================
            // Applied Offers
            // ==============================
            $appliedOffers = OffersUsed::where('cart_id', $cart->id)
                ->where('user_id', $user_id)
                ->pluck('offer_id');


            // ==============================
            // Discount Calculation
            // ==============================
            $discount = 0;

            foreach ($appliedOffers as $offer_id) {

                $offer = Offers::find($offer_id);

                if ($offer) {

                    $shop_total = CartItems::where('cart_id', $cart->id)
                        ->where('shop_id', $offer->shop_id)
                        ->sum('total_price');

                    $discount += ($shop_total * $offer->discount_percentage) / 100;
                }
            }


            // ==============================
            // Delivery Charge
            // ==============================
            $delivery_charge = 50;


            // ==============================
            // Final Amount
            // ==============================
            $final_amount = $item_price + $delivery_charge - $discount;


            // ==============================
            // Cart Items
            // ==============================
            $response = [
                'id'            => $cart->id,
                'user_id'       => $cart->user_id,
                'total_amount'  => $cart->total_amount,
                'items'         => $cart->items->map(function ($item) {
                    return [
                        'id'            => $item->id,
                        'cart_id'       => $item->cart_id,
                        'product_id'    => $item->product_id,
                        'product_name'  => optional($item->product)->product_name,
                        'product_image' => optional($item->product)->product_image,
                        'unit'          => $item->unit,
                        'unit_name'     => optional($item->unitData)->unit_name,
                        'quantity'      => $item->quantity,
                        'price'         => $item->price,
                        'discount_price' => $item->discount_price,
                        'total_price'   => $item->total_price,
                    ];
                })
            ];


            // ==============================
            // Cart Count
            // ==============================
            $cart_count = CartItems::where('cart_id', $cart->id)->count();


            return response()->json([
                'status' => 'success',
                'cart_count' => $cart_count,
                'item_price' => $item_price,
                'delivery_charge' => $delivery_charge,
                'discount' => $discount,
                'final_amount' => $final_amount,
                'offers' => $offers,
                'delivery_address' => $delivery_address,
                'data' => $response
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Parameters Missing'
        ], 400);
    }

    public function updateCartItem(Request $request)
    {
        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');

        if (!$item_id || $quantity === null) {
            return response()->json([
                'status'  => false,
                'message' => 'Parameters Missing'
            ], 400);
        }

        $item = CartItems::find($item_id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Item not found'
            ], 404);
        }

        // ✅ Set exact quantity (not add)
        $item->quantity = $quantity;

        if ($item->quantity <= 0) {
            $item->delete();
        } else {
            $item->total_price = $item->quantity * $item->price;
            $item->save();
        }

        $cart = Cart::with('items')->find($item->cart_id);

        if ($cart) {
            $cart->total_amount = $cart->items->sum('total_price');
            $cart->save();
        }

        return response()->json([
            'status'      => true,
            'message'     => 'Cart updated successfully',
            'cart_total'  => $cart ? $cart->total_amount : 0
        ]);
    }


    public function removeCartItem(Request $request)
    {
        $item_id = $request->input('item_id');
        $user_id = $request->input('user_id');


        if ($item_id != '') {

            $item = CartItems::find($item_id);

            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found'
                ], 404);
            }


            $cart = Cart::find($item->cart_id);

            $item->delete();

            $cart->total_amount = $cart->items()->sum('total_price');
            $cart->save();

            $cart_count = 0;

            if ($user_id) {
                $cart = Cart::where('user_id', $user_id)->first();

                if ($cart) {
                    $cart_count = count(CartItems::where('cart_id', $cart->id)->get());
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Item removed',
                'cart_count'  => $cart_count

            ]);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }

    public function clearCart(Request $request)
    {

        $user_id = $request->input('user_id');

        if ($user_id != '') {

            $cart = Cart::where('user_id', $user_id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart not found'
                ], 404);
            }

            if ($cart) {
                $cart->items()->delete();
                $cart->update(['total_amount' => 0]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Cart cleared'
            ]);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }
}
