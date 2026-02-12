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
        $request->validate([
            'product_id' => 'required',
            'price'      => 'required|numeric',
            'quantity'   => 'required|integer|min:1'
        ]);

        $userId = auth()->id();

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
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
    }

    public function viewCart()
    {
        $cart = Cart::with('items')->where('user_id', auth()->id())->first();

        return response()->json([
            'status' => true,
            'data' => $cart
        ]);
    }

    public function updateCartItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItems::findOrFail($request->item_id);
        $item->quantity = $request->quantity;
        $item->total_price = $item->quantity * $item->price;
        $item->save();

        $cart = Cart::find($item->cart_id);
        $cart->total_amount = $cart->items()->sum('total_price');
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart updated'
        ]);
    }

    public function removeCartItem($item_id)
    {
        $item = CartItems::findOrFail($item_id);
        $cart = Cart::find($item->cart_id);

        $item->delete();

        $cart->total_amount = $cart->items()->sum('total_price');
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Item removed'
        ]);
    }

    public function clearCart()
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->update(['total_amount' => 0]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cart cleared'
        ]);
    }
}
