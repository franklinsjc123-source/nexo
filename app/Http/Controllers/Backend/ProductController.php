<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductImages;
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

        if (Auth::user()->auth_level == 4) {
            $shop_id    =  Shop::where('user_id', auth()->id())->value('id');
            $records    =  Product::with('attributes')->where('shop', $shop_id)->orderBy('id', 'ASC')->get();
        } else {
            $records   =  Product::with('attributes')->orderBy('id', 'ASC')->get();
        }

        return view('backend.products.list', compact('records'));
    }

    public function addProduct($id = '')
    {
        $records            = '';
        $productAttributes  = '';
        $productImages      = '';

        if ($id > 0) {
            $records            =  Product::where('id', $id)->first();
            $productAttributes  =  ProductAttributes::where('product_id', $id)->get();
            $productImages      =  ProductImages::where('product_id', $id)->get();


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

        return view('backend.products.add_edit', compact('records', 'id', 'categoryData', 'shopData', 'unitData', 'productAttributes','productImages'));
    }

    public function storeUpdateProduct(Request $request)
    {
        $id                  = $request->id ?? 0;
        $category            = $request->category ?? '';
        $shop                = $request->shop ?? '';
        $product_name        = $request->product_name ?? '';
        $hsn_code            = $request->hsn_code ?? '';
        $food_type           = $request->food_type ?? '';
        $product_description = $request->product_description ?? '';

        $data = [
            'category'            => $category,
            'shop'                => $shop,
            'product_name'        => $product_name,
            'food_type'           => $food_type,
            'hsn_code'            => $hsn_code,
            'product_description' => $product_description,
            'created_by'          => auth()->id(),
        ];


        if (empty($id)) {

            $insert = Product::create($data);

            if ($request->hasFile('product_image')) {

                foreach ($request->file('product_image') as $key => $file) {

                    $imageName = 'product_' . time() . '_' . $key . '_' .
                        preg_replace('/\s+/', '_', $file->getClientOriginalName());

                    $file->move(public_path('uploads/product'), $imageName);

                    $imagePath = url('uploads/product/' . $imageName);

                    // Save first image as main product image
                    if ($key == 0) {
                        Product::where('id', $insert->id)
                            ->update(['product_image' => $imagePath]);
                    }

                    ProductImages::create([
                        'product_id'    => $insert->id,   // ✅ FIXED
                        'product_image' => $imagePath,
                    ]);
                }
            }


            if ($request->unit) {

                foreach ($request->unit as $i => $unit) {

                    ProductAttributes::create([
                        'product_id'     => $insert->id,
                        'unit'           => $unit ?? 0,
                        'original_price' => $request->original_price[$i] ?? 0,
                        'discount_price' => $request->discount_price[$i] ?? 0,
                    ]);
                }
            }

            return redirect()->route('product')
                ->with('success', 'Product Saved Successfully');
        }



        Product::where('id', $id)->update($data);


        if ($request->hasFile('product_image')) {

            ProductImages::where('product_id', $id)->delete();

            foreach ($request->file('product_image') as $key => $file) {

                $imageName = 'product_' . time() . '_' . $key . '_' .
                    preg_replace('/\s+/', '_', $file->getClientOriginalName());

                $file->move(public_path('uploads/product'), $imageName);

                $imagePath = url('uploads/product/' . $imageName);

                if ($key == 0) {
                    Product::where('id', $id)
                        ->update(['product_image' => $imagePath]);
                }

                ProductImages::create([
                    'product_id'     => $id,
                    'product_image'  => $imagePath,
                ]);
            }
        }

        ProductAttributes::where('product_id', $id)->delete();

        if ($request->unit) {

            foreach ($request->unit as $i => $unit) {

                ProductAttributes::create([
                    'product_id'     => $id,
                    'unit'           => $unit ?? 0,
                    'original_price' => $request->original_price[$i] ?? 0,
                    'discount_price' => $request->discount_price[$i] ?? 0,
                ]);
            }
        }

        return redirect()->route('product')
            ->with('success', 'Product Updated Successfully');
    }


    public function getShopsByCategory(Request $request)
    {

        $shops = Shop::whereRaw("FIND_IN_SET(?, category)", [$request->category_id])
            ->where('status', 1)
            ->get(['id', 'shop_name', 'is_hotel']);
        return $shops;
    }
}
