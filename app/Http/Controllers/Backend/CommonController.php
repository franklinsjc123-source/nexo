<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function updateCommonStatus(Request $request)
    {
        $input       = $request->all();
        $id          = $input['id'];
        $status      = $input['status'];
        $model       = 'App\\Models\\' . $input['model'];
        $updateArray = [
            'status' => $status,
        ];
        if ($input['model'] == 'States') {
            $update = $model::where('state_id', $id)->update(['is_active' => $status]);
        } elseif ($input['model'] == 'FinancialYear') {
            if ($status == 1) {
                $update =    $model::Where('id', $id)->update(array('status' => 1));
                $model::Where('id', '!=', $id)->update(array('status' => 0));
            }
        } else {
            $update = $model::where('id', $id)->update($updateArray);
        }

        if ($update) {
            echo 1;
        }
    }

    public function commonDelete(Request $request)
    {
        $input  = $request->all();
        $id     = $input['id'];
        $model  = 'App\\Models\\' . $input['model'];



        if ($input['model'] ==  'Shop') {
            Product::where('shop', $id)->delete();
            $shop   =  Shop::where('id', $id)->first();
            User::where('id', $shop->user_id)->delete();
        }

        $update = $model::where('id', $id)->delete();



        if ($update) {
            echo 1;
        }
    }

    public function profile()
    {
        $userId    = Auth::user()->id;
        $record    = User::Where('id', $userId)->first();
        return view('backend.profile', compact('record'));
    }

    public function saveProfile(Request $request)
    {

        $input = $request->all();
        $data  = [];
        foreach ($input as $key => $val) {
            if ($key != 'id' && $key != "_token") {
                $data[$key] = $val;
            }
        }

        $id = $input['id'] ?? '';

        $update = User::whereId($id)->update($data);
        //  $msg =  "Profile was Updated By ";
        //  if (!$this->storeLog($msg)) {}
        return redirect()->back()->with('success', 'Profile Updated');
    }

    public function changePassword(Request $request)
    {
        $input   = $request->all();
        $passwd  = $input['password'] ?? '';
        $cpasswd = $input['cpassword'] ?? '';
        $id      = $input['id'] ?? '';

        if ($passwd == $cpasswd) {
            $pwd    = Hash::make($passwd);
            $update = User::whereId($id)->update(['password' => $pwd]);
            //   $msg =  "Password was Updated By ";
            //  if (!$this->storeLog($msg)) {}
            return redirect()->back()->with('success', 'Password Updated');
        } else {
            return redirect()->back()->with('error', 'Password Not Matched');
        }
    }

    public function checkExist(Request $request)
    {
        $query = DB::table($request->table)
            ->where($request->column, $request->value);

        // 🔥 Ignore current ID in edit
        if (!empty($request->id)) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($request->column) . ' already exists'
            ]);
        }

        return response()->json(['status' => true]);
    }
}
