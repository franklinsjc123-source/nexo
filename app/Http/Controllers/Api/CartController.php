<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItems;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {


        $product_id = $request->input('product_id');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $user_id = $request->input('user_id');

        if ($product_id != '' && $price != '' && $quantity != '' && $user_id != '') {

            $cart = Cart::firstOrCreate(
                ['user_id' => $user_id],
                ['total_amount' => 0]
            );

            $item = CartItems::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($item) {
                $item->quantity += $request->quantity;
                $item->total_price = $item->quantity * $item->price;
                $item->save();
            } else {
                CartItems::create([
                    'cart_id'     => $cart->id,
                    'product_id'  => $request->product_id,
                    'price'       => $request->price,
                    'quantity'    => $request->quantity,
                    'total_price' => $request->price * $request->quantity
                ]);
            }

            $cart->total_amount = $cart->items()->sum('total_price');
            $cart->save();

            return response()->json([
                'status' => true,
                'message' => 'Item added to cart'
            ]);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }

    public function viewCart(Request $request)
    {

        $user_id = $request->input('user_id');

        if ($user_id != '') {

            $cart = Cart::with(['items.product:id,product_name'])
                ->where('user_id', $user_id)
                ->first();

            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart not found'
                ], 404);
            }

            $response = [
                'id' => $cart->id,
                'user_id' => $cart->user_id,
                'total_amount' => $cart->total_amount,
                'status' => $cart->status,
                'created_at' => $cart->created_at,
                'updated_at' => $cart->updated_at,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'cart_id' => $item->cart_id,
                        'product_id' => $item->product_id,
                        'product_name' => optional($item->product)->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total_price' => $item->total_price,
                        'status' => $item->status,
                    ];
                })
            ];

            return response()->json([
                'status' => 'success',
                'data' => $response
            ]);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }



    public function updateCartItem(Request $request)
    {

        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');

        if ($item_id != '' && $quantity != '') {

            $item = CartItems::findOrFail($item_id);

            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found'
                ], 404);
            }

            $item->quantity += $quantity;
            $item->total_price = $item->quantity * $item->price;
            $item->save();


            $cart = Cart::with('items')->where('id', $item_id)->first();


            $cart->total_amount = $cart->items->sum('total_price');
            $cart->save();

            return response()->json([
                'status' => true,
                'message' => 'Cart updated successfully'
            ]);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function removeCartItem(Request $request)
    {
        $item_id = $request->input('item_id');


        if ($item_id != '') {

            $item = CartItems::findOrFail($item_id);

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

            return response()->json([
                'status' => true,
                'message' => 'Item removed'
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
