<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function getAllCategory()
    {

        $category = Category::where('status', 1)->get();

        if ($category) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $category);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function getAllShopsByCategory(Request $request)
    {

        $category_id = $request->input('category_id');
        $shops = Shop::where('category', $category_id)->where('status', 1)->get();

        if ($shops) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $shops);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }
}
