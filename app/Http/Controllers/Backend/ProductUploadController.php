<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class ProductUploadController extends Controller
{
    use PermissionCheckTrait;



    public function productUpload($id = '')
    {
        //  if (!$this->checkPermission('Zone')) {
        //     return view('unauthorized');
        // }

        return view('backend.product-upload.upload');
    }

    public function storeUpdateProduct(Request $request)
    {


        $id         = $request->id ?? 0;
        $category   = $request->category ?? '';
        $shop  = $request->shop ?? '';
        $product_name = $request->product_name ?? '';
        $original_price = $request->original_price ?? '';
        $discount_price   = $request->discount_price ?? '';
        $product_description     = $request->product_description ?? '';
        $imageUrl   = $request->old_product_image ?? '';

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $imageName = 'product_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/product'), $imageName);
            $imageUrl = url('uploads/product/' . $imageName);
        }

        $data = [
            'category'                  => $category,
            'shop'                      => $shop,
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
        return Shop::where('category', $request->category_id)
            ->where('status', 1)
            ->get(['id', 'shop_name']);
    }
}
