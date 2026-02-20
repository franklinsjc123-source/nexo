<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class ReferralController extends Controller
{
    use PermissionCheckTrait;

    public function referral()
    {

        if (!$this->checkPermission('Referral')) {
            return view('unauthorized');
        }


        $records = Referral::get();
        return view('backend.referral.list', compact('records'));
    }


    public function addReferral($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = Referral::Where('id', $id)->first();
            $new_referral_code = '';
        } else {

            $new_referral_code = $this->generateReferralCode();
        }
        return view('backend.referral.add_edit', compact('record', 'id','new_referral_code'));
    }

    private function generateReferralCode()
    {
        $last = Referral::orderBy('id', 'desc')->first();
        $number = $last ? $last->id + 1 : 1;

        return 'REF' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function storeUpdateReferral(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;

        $insertArray = array(
            'mobile'        => $input['mobile'],
            'name'          => $input['name'],
            'referral_code' => $input['referral_code'],
        );

        if ($id == 0 || $id == '') {
            $insert = Referral::create($insertArray);
            if ($insert['id'] > 0) {
                return redirect()->route('referral')->with('success', 'Referral Saved Successfully');
            } else {
                return redirect()->route('referral')->with('error', 'Something went wrong!');
            }
        } else {


            $updateArray = array(
                'mobile'         => isset($input['mobile'])    ?  $input['mobile']    : '',
                'referral_code'  => isset($input['referral_code'])    ?  $input['referral_code']    : '',
                'name'           => isset($input['name'])    ?  $input['name']    : '',
            );
            $update = Referral::Where('id', $id)->update($updateArray);
            return redirect()->route('users')->with('success', 'Referral Updated Successfully');
        }
    }
}
