<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class ReportController extends Controller
{
    use PermissionCheckTrait;

    public function ordersReport()
    {

        $records   =  Order::orderBy('id', 'DESC')->get();
        return view('backend.reports.order-report', compact('records'));
    }


      public function directOrdersReport()
    {

        $records   =  Order::orderBy('id', 'DESC')->get();
        return view('backend.reports.direct-order-report', compact('records'));
    }



}
