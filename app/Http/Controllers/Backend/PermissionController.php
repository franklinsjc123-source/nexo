<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\PermissionCheckTrait;
class PermissionController extends Controller
{
    use PermissionCheckTrait;
    public function permission(Request $request)
    {
         if (!$this->checkPermission('permission')) {
            return view('unauthorized');
        }

         $input = $request->all();
        $fields = isset($input['columns']) ? $input['columns']:'';
        $tblsearch = isset($input['tblsearch']) ? $input['tblsearch']:'';
        $sortOrder  = $request->query('order');
        $date  = $request->query('date');

        $records = Permission::query()->leftjoin('permission_category as pc','pc.id','=','permissions.category')
         ->get(['permissions.id','permissions.display_name','pc.name','permissions.category','permissions.name as permission']);
        $category = PermissionCategory::get();
        return view('backend.permission.list', compact('records','category','tblsearch'));
    }


    public function addPermission($id = '')
    {
        $record = '';
         $categoryData = PermissionCategory::get();
        if ($id > 0) {
            $record = Permission::WHere('id', $id)->first();
        }
        return view('backend.permission.add_edit', compact('record', 'id','categoryData'));
    }

    // public function updatePermission(Request $request)
    // {
    //     $input = $request->all();
    //      if(isset($input["_token"])){
    //             $permissions = $input['permissions'];
    //             $user        = $input['user'];
    //             $user = User::where('id', $user)->first('id');

    //             // Assign Permission to this user
    //             $user->syncPermissions($permissions);
    //             $msg =   "Permission Added Successfully";
    //             $badge = "success";
    //             return redirect()->back()->with($badge, $msg);
    //      }
    // }


    public function updatePermission(Request $request)
{



        $input = $request->all();
         if(isset($input["_token"])){

        $user = User::findOrFail($request->user);

        // Always default to empty array
        $permissions = $request->input('permissions', []);
        $permissions_action = $request->input('permissions_action', []);

        // Merge both (if action not checked → empty array)
        $allPermissions = array_unique(
            array_merge($permissions, $permissions_action)
        );

       

        // Sync permissions
        $user->syncPermissions($allPermissions);



        return redirect()->back()->with('success', 'Permission Added Successfully');
    }
}

    public function assignPermission(Request $request)
    {
        $users = User::where('status',1)->get(['name','id']);
        // $users = User::Where('status',1)->get(['name','id']);
        $msg = $badge = '';
            $permissions = Permission::leftjoin('permission_category as pc','pc.id','=','permissions.category')
            // ->Orderby('permissions.name','ASC')
            ->get(['permissions.id','permissions.display_name','pc.id as category','pc.name']);
            $permissionArr = array();
            foreach($permissions as $key=>$val)
            {
               $permissionArr[$val->name][] = array(
                   "id"              => $val->id,
                   "cid"             => $val->category,
                   "category"        => $val->name,
                   "permission_name" => $val->display_name,
               );
            }
            return view('backend.permission',compact('users','permissionArr','msg'));
    }

    public function getPermission($id)
    {

        $permissions = Permission::leftjoin('permission_category as pc','pc.id','=','permissions.category')
                                  ->leftjoin('permission_user as pu','pu.permission_id','=','permissions.id')
                                  ->Where('pu.user_id',$id)
        ->get(['permissions.id','permissions.display_name','pc.id as category','pc.name']);

        return $permissions ?? 0;
    }

    public function storeUpdatePermission(Request $request)
    {
        $input = $request->all();
        $id    = $input['id'];
        $request->validate([
            'category' => "required",
            'permission_name' => "required",
        ]);
        $uppercaseString = ucwords($input['permission_name']);

        $msg = '';
        if ($id == '') {

            $insertArray = [
                'name'         =>  $input['permission_name'],
                'category'     =>  $input['category'],
                'display_name' => $uppercaseString,
               // 'status'        => 1,
               // 'created_by'    => Auth::user()->id,
            ];

            $insert = Permission::create($insertArray);
            $msg    = ($insert['id'] > 0) ? "Permission Stored Successfully" : "Something Went Wrong";
            /** Insert  */
            return redirect()->route('permission')->with('success', 'Permission Stored Successfully');
        } else {
            /** Update */
            $msg         = "Permission Updated Successfully";
            $insertArray = [
                'name'         =>  $input['permission_name'],
                'category'     =>  $input['category'],
                'display_name' => $uppercaseString,
               // 'status'        => 1,
               // 'updated_by'    => Auth::user()->id,
            ];
            $update = Permission::whereId($id)->update($insertArray);
            $msg    = ($update > 0) ? "Permission Updated Successfully" : "Something Went Wrong";

            return redirect()->route('permission')->with('success', $msg);
        }

        return redirect()->back()->with('msg', $msg);
    }
}
