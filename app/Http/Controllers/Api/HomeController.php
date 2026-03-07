<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\Product;
use App\Models\PinCode;
use App\Models\Cart;
use App\Models\CartItems;


use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getAllCategory(Request $request)
    {

        $category   = Category::where('status', 1)->get();
        if ($category) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $category);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }



    public function getHomePageDetails(Request $request)
    {

        $pincode = $request->input('pincode');
        $user_id = $request->input('user_id');

        if ($pincode) {

            $checkPincodeExistence = PinCode::where('pincode', $pincode)->where('status', 1)->exists();

            $category   = Category::where('status', 1)->get();
            $shops      = Shop::where('status', 1)->inRandomOrder()->get();
            $slider     = Slider::where('status', 1)->get();

            $cart_count = 0;
            if ($user_id) {
                $cart = Cart::where('user_id', $user_id)->first();

                if ($cart) {
                    $cart_count = count(CartItems::where('cart_id', $cart->id)->get());
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Data received successfully',
                'delivery' => !$checkPincodeExistence ? 'not available' : 'available.',
                'cart_count' => $cart_count,
                'data'    => [
                    'categories' => $category,
                    'shops'      => $shops,
                    'sliders'    => $slider,
                ]
            ], 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Fetching pincode Error');
            return response()->json(array($error_array), 400);
        }
    }



    public function getAllShopsByCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        if ($category_id) {
            $shops = Shop::whereRaw("FIND_IN_SET(?, category)", [$category_id])->where('status', 1)->get();
        } else {
            $shops = Shop::where('status', 1)->get();
        }


        if ($shops->isNotEmpty()) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $shops);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Records not found');
            return response()->json(array($error_array), 400);
        }
    }

    public function getAllProductsByShop(Request $request)
    {
        $shop_id     = $request->input('shop_id');
        $user_id     = $request->input('user_id');
        $category_id = $request->input('category_id');

        if (!$shop_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Shop ID is required'
            ], 400);
        }

        if (!$user_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ID is required'
            ], 400);
        }

        $cart = Cart::where('user_id', $user_id)->first();

        $cartItems = [];
        if ($cart) {
            $cartItems = CartItems::where('cart_id', $cart->id)
                ->get()
                ->keyBy(function ($item) {
                    return $item->product_id . '_' . $item->unit;
                });
        }

        $query = Product::with([
            'categoryData:id,category_name',
            'shopData:id,shop_name',
            'attributes.unitData:id,unit_name'
        ])
            ->where('shop', $shop_id)
            ->where('status', 1);

        if (!empty($category_id)) {
            $query->where('category', $category_id);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'No products found',
                'data'    => []
            ], 200);
        }

        $data = $products->map(function ($product) use ($cartItems) {

            return [
                'id'                  => $product->id,
                'category_id'         => $product->category,
                'category_name'       => optional($product->categoryData)->category_name,
                'shop_id'             => $product->shop,
                'shop_name'           => optional($product->shopData)->shop_name,
                'product_name'        => $product->product_name,
                'product_description' => $product->product_description,
                'product_image'       => $product->product_image,
                'status'              => $product->status,

                'quantity_data' => $product->attributes
                    ->where('original_price', '>', 0)
                    ->map(function ($attr) use ($product, $cartItems) {

                        $key = $product->id . '_' . $attr->unit;

                        $cartItem = $cartItems[$key] ?? null;

                        $cartQuantity = $cartItem ? $cartItem->quantity : 0;
                        $cartItemId   = $cartItem ? $cartItem->id : null;

                        return [
                            'unit_id'        => $attr->unit,
                            'unit_name'      => optional($attr->unitData)->unit_name,
                            'original_price' => $attr->original_price,
                            'discount_price' => $attr->discount_price,
                            'discount_percentage' => $attr->original_price > 0 ? round((($attr->original_price - $attr->discount_price) / $attr->original_price) * 100) : 0,
                            'cart_item_id'   => $cartItemId,
                            'cart_quantity'  => $cartQuantity
                        ];
                    })->values()
            ];
        });

        $cart_count = 0;
        if ($user_id) {
            $cart = Cart::where('user_id', $user_id)->first();

            if ($cart) {
                $cart_count = count(CartItems::where('cart_id', $cart->id)->get());
            }
        }




        return response()->json([
            'status'  => 'success',
            'message' => 'Data received successfully',
            'cart_count'  => $cart_count,
            'data'    => $data
        ], 200);
    }


    public function productDetail(Request $request)
    {

        $product_id = $request->input('product_id');
        $user_id    = $request->input('user_id');

        $product = Product::with(['attributes', 'images'])
            ->where('id', $product_id)
            ->where('status', 1)
            ->first();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Records not found'
            ], 400);
        }

        // Get user cart
        $cart = Cart::where('user_id', $user_id)->first();

        $cartItems = [];
        if ($cart) {
            $cartItems = CartItems::where('cart_id', $cart->id)
                ->get()
                ->keyBy(function ($item) {
                    return $item->product_id . '_' . $item->unit;
                });
        }

        // Modify attributes
        $attributes = $product->attributes->map(function ($attr) use ($cartItems) {

            $key = $attr->product_id . '_' . $attr->unit;

              $cartItem = $cartItems[$key] ?? null;

                    $cartQuantity = $cartItem ? $cartItem->quantity : 0;
                    $cartItemId   = $cartItem ? $cartItem->id : null;

            $discount_percentage = 0;

            if ($attr->original_price > 0) {
                $discount_percentage = round(
                    (($attr->original_price - $attr->discount_price) / $attr->original_price) * 100
                );
            }

            return [
                'id' => $attr->id,
                'product_id' => $attr->product_id,
                'unit' => $attr->unit,
                'unit_name'      => optional($attr->unitData)->unit_name,
                'original_price' => $attr->original_price,
                'discount_price' => $attr->discount_price,
                'discount_percentage' => $discount_percentage,
                 'cart_item_id'   => $cartItemId,
                'cart_quantity' => $cartQuantity
            ];
        });

        $data = [
            'id' => $product->id,
            'category' => $product->category,
            'shop' => $product->shop,
            'product_name' => $product->product_name,
            'product_description' => $product->product_description,
            'product_image' => $product->product_image,
            'attributes' => $attributes,
            'images' => $product->images
        ];


        $cart_count = 0;

        if ($user_id) {
            $cart = Cart::where('user_id', $user_id)->first();

            if ($cart) {
                $cart_count = count(CartItems::where('cart_id', $cart->id)->get());
            }
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'cart_count'  => $cart_count,
            'data' => $data
        ], 200);
    }
}
