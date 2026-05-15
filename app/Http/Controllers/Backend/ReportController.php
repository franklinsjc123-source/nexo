<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DirectOrder;
use App\Models\DeliveryPerson;

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

    public function deliveryReport(Request $request)
    {
        $query = Order::query()->where('order_status', 2);
        $query->whereYear('created_at', date('Y'));

        $month = $request->has('month') ? $request->month : date('m');

        if ($month != "") {
            $query->whereMonth('created_at', $month);
        }

        if ($request->filled('delivery_person_id')) {
            $query->where('deliver_person_id', $request->delivery_person_id);
        }

        $records = $query->orderBy('id', 'DESC')->get();
        $delivery_persons = DeliveryPerson::all();

        return view('backend.reports.delivery-report', compact('records', 'delivery_persons'));
    }
}
