<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PinCode;
use Illuminate\Http\Request;

use App\Http\Traits\PermissionCheckTrait;

class PincodeController extends Controller
{
    use PermissionCheckTrait;

    public function pincode()
    {
        //  if (!$this->checkPermission('Zone')) {
        //     return view('unauthorized');
        // }
        $records   =  PinCode::orderBy('id', 'ASC')->get();
        return view('backend.pincode.list', compact('records'));
    }

    public function addPincode($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = PinCode::WHere('id', $id)->first();
        }
        return view('backend.pincode.add_edit', compact('record', 'id'));
    }

    public function storeUpdatePincode(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;

        $dataArr = [];
        foreach ($input as $key => $val) {
            if ($key != 'id')
                $dataArr[$key] = $val;
        }
        // dd($dataArr);
        if ($id == 0 || $id == '') {
            $insert = PinCode::create($dataArr);
            if ($insert['id'] > 0) {
                return redirect()->route('pincode')->with('success', 'Pincode Saved Successfully');
            } else {
                return redirect()->route('pincode')->with('error', 'Something went wrong!');
            }
        } else {
            $updateArray = array(
                'pincode'         => isset($input['pincode'])    ?  $input['pincode']    : '',
            );
            $update = PinCode::Where('id', $id)->update($updateArray);
            return redirect()->route('pincode')->with('success', 'Pincode Updated Successfully');
        }

    }


}
