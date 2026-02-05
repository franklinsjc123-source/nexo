<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class CategoryController extends Controller
{
    use PermissionCheckTrait;

    public function category()
    {
        //  if (!$this->checkPermission('Zone')) {
        //     return view('unauthorized');
        // }
        $records   =  Category::orderBy('id', 'ASC')->get();
        return view('backend.category.list', compact('records'));
    }

    public function addCategory($id = '')
    {
        $records = '';
        if ($id > 0) {
            $records   =  Category::where('id', $id )->first();

        }

        return view('backend.category.add_edit', compact('records', 'id'));
    }

    public function storeUpdateCategory(Request $request)
    {
        $request->validate([
            'photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id' => 'nullable|integer'
        ]);

        $id = $request->id ?? 0;
        $category_name = $request->category_name ?? 0;
        $imageUrl = $request->old_photo_path ?? '';

        if ($request->hasFile('photo_path')) {
            $file = $request->file('photo_path');
            $imageName = 'slider_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/slider'), $imageName);
            $imageUrl = url('uploads/slider/' . $imageName);
        }

        $data = [
            'category_name' => $category_name,
            'file_path' => $imageUrl,
        ];

        if (empty($id)) {
            $insert = Category::create($data);

            return redirect()
                ->route('category')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Category Saved Successfully' : 'Something went wrong!'
                );
        }

        Category::where('id', $id)->update($data);

        return redirect()->route('category')->with('success', 'Category Updated Successfully');
    }
}
