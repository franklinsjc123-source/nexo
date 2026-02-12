<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\PermissionCheckTrait;

class DeliveryPersonController extends Controller
{
    use PermissionCheckTrait;

    public function deliveryPerson()
    {

        if (!$this->checkPermission('Delivery-Persons')) {
            return view('unauthorized');
        }

        $records = DeliveryPerson::get();
        return view('backend.delivery_person.list', compact('records'));

    }


    public function addDeliveryPerson($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = DeliveryPerson::first();
        }
        return view('backend.delivery_person.add_edit', compact('record', 'id'));
    }

    public function storeUpdateDeliveryPerson(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;

        $insertArray = array(
            'mobile'       => $input['mobile'],
            'name'       => $input['name'],
            'email'        => $input['email'],
            'password'     => Hash::make($input['password']),
        );

        if ($id == 0 || $id == '') {
            $insert = DeliveryPerson::create($insertArray);
            if ($insert['id'] > 0) {
                return redirect()->route('deliveryPerson')->with('success', 'Delivery Person Saved Successfully');
            } else {
                return redirect()->route('deliveryPerson')->with('error', 'Something went wrong!');
            }
        } else {

            if($input['password']){
                  $updateArray = [ 'password'     => Hash::make($input['password'])];
            }
            $updateArray = array(
                'mobile'         => isset($input['mobile'])    ?  $input['mobile']    : '',
                'email'         => isset($input['email'])    ?  $input['email']    : '',
                'name'         => isset($input['name'])    ?  $input['name']    : '',
            );
            $update = DeliveryPerson::Where('id', $id)->update($updateArray);
            return redirect()->route('deliveryPerson')->with('success', 'Delivery Person  Updated Successfully');
        }
    }
}
