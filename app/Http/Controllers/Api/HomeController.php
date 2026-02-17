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

            $checkPincodeExistence = PinCode::where('pincode', $pincode)->exists();

            $category   = Category::where('status', 1)->get();
            $shops      = Shop::where('status', 1)->inRandomOrder()->limit(8)->get();
            $slider     = Slider::where('status', 1)->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data received successfully',
                'delivery' => !$checkPincodeExistence ? 'Delivery is not available for this pincode.' : 'Delivery is available.',
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
        $shops = Shop::where('category', $category_id)->where('status', 1)->get();

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

        $products = Product::with([
            'categoryData:id,category_name',
            'shopData:id,shop_name',
            'unitData:id,unit_name'
        ])
            ->where('shop', $shop_id)
            ->where('status', 1)
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No products found'
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
                'qty'                 => $product->qty,
                'unit_id'             => $product->unit,
                'unit_name'           => optional($product->unitData)->unit_name,
                'original_price'      => $product->original_price,
                'discount_price'      => $product->discount_price,
                'product_description' => $product->product_description,
                'product_image'       => $product->product_image,
                'status'              => $product->status,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $data
        ], 200);
    }
}
