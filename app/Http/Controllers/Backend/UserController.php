<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users()
    {
        $records = User::Where('auth_level', 2)->get();
        return view('backend.users.list', compact('records'));
    }


    public function addUser($id = '')
    {
        $record = '';
        if ($id > 0) {
            $record = User::Where('id', $id)->first();
        }
        return view('backend.users.add_edit', compact('record', 'id'));
    }

    public function storeUpdateUser(Request $request)
    {
        $input     = $request->all();
        $id        = isset($input['id']) ? $input['id'] : 0;

        $insertArray = array(
            'mobile'       => $input['mobile'],
            'name'       => $input['name'],
            'email'        => $input['email'],
            'auth_level'        => 2,
            'password'     => Hash::make($input['password']),
        );

        if ($id == 0 || $id == '') {
            $insert = User::create($insertArray);
            if ($insert['id'] > 0) {
                return redirect()->route('users')->with('success', 'User Saved Successfully');
            } else {
                return redirect()->route('users')->with('error', 'Something went wrong!');
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
            $update = User::Where('id', $id)->update($updateArray);
            return redirect()->route('users')->with('success', 'User Updated Successfully');
        }
    }
}
