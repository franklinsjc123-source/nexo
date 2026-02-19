<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    use PermissionCheckTrait;

    public function shop()
    {
        if (!$this->checkPermission('Shop')) {
            return view('unauthorized');
        }

        $records   =  Shop::orderBy('id', 'ASC')->get();
        
        // dd( $records);

        return view('backend.shop.list', compact('records'));
    }

    public function addShop($id = '')
    {
        $records = '';
        if ($id > 0) {
            $records   =  Shop::where('id', $id)->first();
        }

        $categoryData   =  Category::orderBy('category_name', 'ASC')->get();

        return view('backend.shop.add_edit', compact('records', 'id', 'categoryData'));
    }

    public function storeUpdateShop(Request $request)
    {
        $request->validate([
            'photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id' => 'nullable|integer'
        ]);

        $id         = $request->id ?? 0;
        $user_id    = $request->user_id ?? 0;
        $email      = $request->email ?? '';
        $shop_name  = $request->shop_name ?? '';
        $contact_no = $request->contact_no ?? '';
        $start_time = $request->start_time ?? '';
        $end_time   = $request->end_time ?? '';
        $gst_no     = $request->gst_no ?? '';
        $address    = $request->address ?? '';
        $imageUrl   = $request->old_photo_path ?? '';

        if (is_array($request->category)) {
            $category = implode(',', $request->category);
        } else {
            $category = $request->category ?? '';
        }



        if ($request->hasFile('photo_path')) {
            $file = $request->file('photo_path');
            $imageName = 'shop_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/shop'), $imageName);
            $imageUrl = url('uploads/shop/' . $imageName);
        }


        $userArray = [
            'name'       => $shop_name,
            'email'      => $email,
            'mobile'     => $contact_no,
            'auth_level' => 4,
        ];

        if ($request->password) {
            $userArray['password'] = Hash::make($request->password);
        }

        if ($user_id > 0) {

            User::where('id', $user_id)->update($userArray);
        } else {

            $user = User::create($userArray);
            $user_id = $user->id;
        }
        $data = [
            'category'      => $category,
            'user_id'       => $user_id,
            'shop_name'     => $shop_name,
            'contact_no'    => $contact_no,
            'contact_no'    => $contact_no,
            'start_time'    => $start_time,
            'end_time'      => $end_time,
            'gst_no'        => $gst_no,
            'address'       => $address,
            'file_path'     => $imageUrl,
        ];

        if (empty($id)) {
            $insert = Shop::create($data);

            return redirect()
                ->route('shop')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Shop Saved Successfully' : 'Something went wrong!'
                );
        }

        Shop::where('id', $id)->update($data);

        return redirect()->route('shop')->with('success', 'Shop Updated Successfully');
    }
}
