<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class CompanyController extends Controller
{

    use PermissionCheckTrait;

    public function company()
    {

        if (!$this->checkPermission('Company')) {
            return view('unauthorized');
        }

        $records = Company::get();

        return view('backend.company.list', compact('records'));
    }

    public function addCompany($id = '')
    {

        $record = '';
        if ($id > 0) {
            $record = Company::WHere('id', $id)->first();
        }
        return view('backend.company.add_edit', compact('record','id'));
    }

    public function storeUpdateCompany(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;


        $id = $request->id ?? 0;
        $imageUrl = $request->old_company_logo ?? '';

        if ($request->file('company_logo') != '') {

            $file = $request->file('company_logo');
            $imageName = 'company_logo_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/company_logo'), $imageName);
            $imageUrl = url('uploads/company_logo/' . $imageName);

        }

        // dd($imageUrl);


        $updateArray = array(
            'company_name'      => isset($input['company_name'])     ?  $input['company_name']     : '',
            'phone'             => isset($input['phone'])     ?  $input['phone']     : '',
            'email'             => isset($input['email'])     ?  $input['email']     : '',
            'delivery_charge'   => isset($input['delivery_charge'])     ?  $input['delivery_charge']     : '',
            'company_address'   => isset($input['company_address'])      ?  $input['company_address']      : '',
            'pincode'           => isset($input['pincode'])  ?  $input['pincode']  : '',
            'state'             => isset($input['state'])   ?  $input['state']   : '',
            'pan_no'            => isset($input['pan_no'])    ?  $input['pan_no']    : '',
            'gst_no'            => isset($input['gst_no'])    ?  $input['gst_no']    : '',
            'logo'              => $imageUrl    ?  $imageUrl   : '',
        );

        $update = Company::Where('id', $id)->update($updateArray);
        return redirect()->route('company')->with('success', 'Company Updated Successfully');

    }


}
