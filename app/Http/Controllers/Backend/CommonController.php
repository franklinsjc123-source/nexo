<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Zone;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Route;
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

        }elseif($input['model'] == 'FinancialYear'){
                if($status == 1)
                {
                 $update =    $model::Where('id',$id)->update(array('status' => 1));
                     $model::Where('id', '!=', $id)->update(array('status' => 0));
                }
        }
        else {
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
        $update = $model::where('id', $id)->delete();

        if($input['model'] ==  'Shop'){
            'App\\Models\\Product'::where('shop', $id)->delete();
        }


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
        $table  = $request->table;
        $column = $request->column;
        $value  = $request->value;

        // Check duplicate
        $query = \DB::table($table)->where($column, $value);

        $exists = $query->exists();

        // If record exists → return duplicate message
        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => ucfirst(str_replace('_',' ', $column)) . " already exists!"
            ]);
        }

    }








    public function getZoneBasedData(Request $request)
    {
        $zoneId = $request->zone_id;

        $companies = Company::where('zonal', $zoneId)
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        $drivers = Driver::where('zonal', $zoneId)
            ->select('id', 'driver_name')
            ->orderBy('driver_name')
            ->get();

        $vehicles = Vehicle::where('zonal', $zoneId)
            ->select('id', 'vehicle_no')
            ->orderBy('vehicle_no')
            ->get();

        return response()->json([
            'companies' => $companies,
            'vehicles' => $vehicles,
            'drivers'   => $drivers
        ]);
    }

    public function getCompanyBasedData(Request $request)
    {
        $companyId = $request->company_id;

        $routes = Route::where('company', $companyId)
            ->select('id', 'route_name')
            ->orderBy('route_name')
            ->get();

        $vehicles = Vehicle::where('company', $companyId)
                        ->select('id', 'vehicle_no')
                        ->orderBy('vehicle_no')
                        ->get();



        return response()->json([ 'routes' => $routes, 'vehicles' => $vehicles]);
    }


    public function getVehicleBasedData(Request $request)
    {
        $vehicleId = $request->vehicle_id;

        $vehicle = Vehicle::where('id', $vehicleId)->first();

        $zones = Zone::where('id', $vehicle->zonal ?? 0)
            ->select('id', 'zone_name')->get();

        $companies = Company::where('id', $vehicle->company ?? 0)
            ->select('id', 'full_name')->get();

        return response()->json([ 'zones' => $zones, 'companies' => $companies ]);
    }



}
