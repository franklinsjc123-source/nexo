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

        if (!$this->checkPermission('Company-Settings')) {
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

        }else{
             $imageUrl  =  $input['old_company_logo'];
        }




        if ($request->file('qr_code') != '') {

            $file = $request->file('qr_code');
            $imageName = 'qr_code_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/qr_code'), $imageName);
            $qrImageUrl = url('uploads/qr_code/' . $imageName);

        }else{

             $qrImageUrl = $input['old_qr_code'];

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
            'fssai_no'          => isset($input['fssai_no'])    ?  $input['fssai_no']    : '',
            'gst_no'            => isset($input['gst_no'])    ?  $input['gst_no']    : '',
            'terms'             => isset($input['terms'])    ?  $input['terms']    : '',
            'invoice_no'        => isset($input['invoice_no'])    ?  $input['invoice_no']    : '',
            'direct_invoice_no' => isset($input['direct_invoice_no'])    ?  $input['direct_invoice_no']    : '',
            'bank_name'         => isset($input['bank_name'])    ?  $input['bank_name']    : '',
            'branch_name'       => isset($input['branch_name'])    ?  $input['branch_name']    : '',
            'ifsc'              => isset($input['ifsc'])    ?  $input['ifsc']    : '',
            'account_no'        => isset($input['account_no'])    ?  $input['account_no']    : '',
            'logo'              => $imageUrl    ?  $imageUrl   : '',
            'qr_code'           => $qrImageUrl    ?  $qrImageUrl   : '',

        );

        $update = Company::Where('id', $id)->update($updateArray);
        return redirect()->route('company')->with('success', 'Company Updated Successfully');

    }


}
