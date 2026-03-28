<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DirectOrder;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class ReportController extends Controller
{
    use PermissionCheckTrait;

    public function ordersReport(Request $request)
    {
        $query = Order::query();

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $records = $query->orderBy('id', 'DESC')->get();
        return view('backend.reports.order-report', compact('records'));
    }

    public function directOrdersReport(Request $request)
    {
        $query = DirectOrder::query();

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $records = $query->orderBy('id', 'DESC')->get();
        return view('backend.reports.direct-order-report', compact('records'));
    }



}
