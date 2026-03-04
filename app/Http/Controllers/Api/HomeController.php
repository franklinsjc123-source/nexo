<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\Product;
use App\Models\PinCode;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getHomePageDetails(Request $request)
    {

        $pincode = $request->input('pincode');

        if ($pincode) {

            $checkPincodeExistence = PinCode::where('pincode', $pincode)->where('status', 1)->exists();

            $category   = Category::where('status', 1)->get();
            $shops      = Shop::where('status', 1)->inRandomOrder()->limit(8)->get();
            $slider     = Slider::where('status', 1)->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data received successfully',
                'delivery' => !$checkPincodeExistence ? 'not available' : 'available.',
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
        $shops = Shop::whereRaw("FIND_IN_SET(?, category)", [$category_id])->where('status', 1)->get();

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
        $shop_id = $request->input('shop_id');
        $category_id = $request->input('category_id');

        $products = Product::with([
            'categoryData:id,category_name',
            'shopData:id,shop_name',
            'attributes.unitData:id,unit_name'
        ])
            ->where('shop', $shop_id)
            ->where('category', $category_id)
            ->where('status', 1)
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No products found',
                'data' => []
            ], 200);
        }

        $data = $products->map(function ($product) {

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

                // 🔥 Multiple attributes array
                'quantity_data' => $product->attributes->map(function ($attr) {
                    return [
                        'unit_id'        => $attr->unit,
                        'unit_name'      => optional($attr->unitData)->unit_name,
                        'original_price' => $attr->original_price,
                        'discount_price' => $attr->discount_price,
                    ];
                })->values()

            ];
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Data received successfully',
            'data'    => $data
        ], 200);
    }
}
