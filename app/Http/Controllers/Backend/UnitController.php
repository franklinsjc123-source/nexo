<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

use App\Http\Traits\PermissionCheckTrait;

class UnitController extends Controller
{
    use PermissionCheckTrait;

    public function unit()
    {
         if (!$this->checkPermission('Unit')) {
            return view('unauthorized');
        }
        $records   =  Unit::orderBy('id', 'ASC')->get();
        return view('backend.unit.list', compact('records'));
    }

    public function addUnit($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = Unit::WHere('id', $id)->first();
        }
        return view('backend.unit.add_edit', compact('record', 'id'));
    }

    public function storeUpdateUnit(Request $request)
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
            $insert = Unit::create($dataArr);
            if ($insert['id'] > 0) {
                return redirect()->route('unit')->with('success', 'Unit Saved Successfully');
            } else {
                return redirect()->route('unit')->with('error', 'Something went wrong!');
            }
        } else {
            $updateArray = array(
                'unit_name'         => isset($input['unit_name'])    ?  $input['unit_name']    : '',
            );
            $update = Unit::Where('id', $id)->update($updateArray);
            return redirect()->route('unit')->with('success', 'Unit Updated Successfully');
        }

    }


}
