<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

use App\Http\Traits\PermissionCheckTrait;

class TaxController extends Controller
{
    use PermissionCheckTrait;

    public function tax()
    {
         if (!$this->checkPermission('Tax')) {
            return view('unauthorized');
        }
        $records   =  Tax::orderBy('id', 'ASC')->get();
        return view('backend.tax.list', compact('records'));
    }

    public function addTax($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = Tax::WHere('id', $id)->first();
        }
        return view('backend.tax.add_edit', compact('record', 'id'));
    }

    public function storeUpdateTax(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;

        $dataArr = [];
        foreach ($input as $key => $val) {
            if ($key != 'id')
                $dataArr[$key] = $val;
        }

        if ($id == 0 || $id == '') {
            $insert = Tax::create($dataArr);
            if ($insert['id'] > 0) {
                return redirect()->route('unit')->with('success', 'Tax Saved Successfully');
            } else {
                return redirect()->route('unit')->with('error', 'Something went wrong!');
            }
        } else {
            $updateArray = array(
                'tax_percentage'         => isset($input['tax_percentage'])    ?  $input['tax_percentage']    : '',
            );
            $update = Tax::Where('id', $id)->update($updateArray);
            return redirect()->route('tax')->with('success', 'Tax Updated Successfully');
        }

    }


}
