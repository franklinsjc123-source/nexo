<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class ProductController extends Controller
{
    use PermissionCheckTrait;

    public function product()
    {
        if (!$this->checkPermission('Product')) {
            return view('unauthorized');
        }


        $records   =  Product::orderBy('id', 'ASC')->get();

        return view('backend.products.list', compact('records'));
    }

    public function addProduct($id = '')
    {
        $records = '';
        if ($id > 0) {
            $records   =  Product::where('id', $id)->first();
        }

        if (Auth::user()->auth_level == 4) {

            $categoryIds      = Shop::where('user_id', auth()->id())->value('category');
            $categoryIdsArray = $categoryIds ? explode(',', $categoryIds) : [];
            $categoryData     = Category::where('status', 1)->whereIn('id', $categoryIdsArray)->orderBy('category_name', 'ASC')->get();

        } else {
            $categoryData   =  Category::where('status', 1)->orderBy('category_name', 'ASC')->get();
        }

        $shopData       =  Shop::where('status', 1)->orderBy('shop_name', 'ASC')->get();
        $unitData       =  Unit::where('status', 1)->orderBy('unit_name', 'ASC')->get();

        return view('backend.products.add_edit', compact('records', 'id', 'categoryData', 'shopData', 'unitData'));
    }

    public function storeUpdateProduct(Request $request)
    {

        $id                  = $request->id ?? 0;
        $category            = $request->category ?? '';
        $shop                = $request->shop ?? '';
        $qty                 = $request->qty ?? '';
        $unit                = $request->unit ?? '';
        $product_name        = $request->product_name ?? '';
        $original_price      = $request->original_price ?? '';
        $discount_price      = $request->discount_price ?? '';
        $product_description = $request->product_description ?? '';
        $imageUrl            = $request->old_product_image ?? '';

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $imageName = 'product_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/product'), $imageName);
            $imageUrl = url('uploads/product/' . $imageName);
        }

        $data = [
            'category'                  => $category,
            'shop'                      => $shop,
            'qty'                       => $qty,
            'unit'                      => $unit,
            'product_name'              => $product_name,
            'original_price'            => $original_price,
            'discount_price'            => $discount_price,
            'product_description'       => $product_description,
            'product_image'                 => $imageUrl,
        ];

        if (empty($id)) {
            $insert = Product::create($data);

            return redirect()
                ->route('product')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Product Saved Successfully' : 'Something went wrong!'
                );
        }

        Product::where('id', $id)->update($data);

        return redirect()->route('product')->with('success', 'Product Updated Successfully');
    }


    public function getShopsByCategory(Request $request)
    {

        $shops = Shop::whereRaw("FIND_IN_SET(?, category)", [$request->category_id])
            ->where('status', 1)
            ->get(['id', 'shop_name']);



        return $shops;
    }
}
