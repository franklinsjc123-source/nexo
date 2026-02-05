<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class SliderController extends Controller
{
    use PermissionCheckTrait;

    public function slider()
    {
        //  if (!$this->checkPermission('Zone')) {
        //     return view('unauthorized');
        // }
        $records   =  Slider::orderBy('id', 'ASC')->get();
        return view('backend.slider.list', compact('records'));
    }

    public function addSlider($id = '')
    {
        $record = '';
        if ($id > 0) {
            $records   =  Slider::where('id', $id )->first();

        }

        return view('backend.slider.add_edit', compact('records', 'id'));
    }

    public function storeUpdateSlider(Request $request)
    {
        $request->validate([
            'slider_photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id' => 'nullable|integer'
        ]);

        $id = $request->id ?? 0;
        $imageUrl = $request->old_slider_photo_path ?? '';

        if ($request->hasFile('slider_photo_path')) {
            $file = $request->file('slider_photo_path');
            $imageName = 'slider_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/slider'), $imageName);
            $imageUrl = url('uploads/slider/' . $imageName);
        }

        $data = [
            'file_path' => $imageUrl,
        ];

        if (empty($id)) {
            $insert = Slider::create($data);

            return redirect()
                ->route('slider')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Slider Saved Successfully' : 'Something went wrong!'
                );
        }

        Slider::where('id', $id)->update($data);

        return redirect()->route('slider')->with('success', 'Slider Updated Successfully');
    }
}
