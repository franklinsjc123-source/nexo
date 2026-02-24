<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\DirectOrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Company;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class DirectOrderController extends Controller
{
    use PermissionCheckTrait;



    public function directOrders()
    {
        if (!$this->checkPermission('Orders')) {
            return view('unauthorized');
        }

        if (Auth::user()->auth_level == 4) {
            $shop_id   =  Shop::where('user_id', auth()->id())->value('id');
            $records   =  DirectOrder::where('shop_id', $shop_id)->orderBy('id', 'DESC')->get();
        } else {
            $records   =  DirectOrder::orderBy('id', 'DESC')->get();
        }

        return view('backend.direct_order.direct_order_list', compact('records'));
    }


    public function updateOrderStatus(Request $request)
    {
        DirectOrder::where('id', $request->order_id)
            ->update(['order_status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully'
        ]);
    }



    public function addDirectOrderBill($id = '')
    {
        $record = '';

        if ($id > 0) {
            $record = DirectOrder::where('id', $id)->first();
            $order_items = DirectOrderItems::where('order_id', $id)->get();
        }

        return view('backend.direct_order.add_edit', compact('record', 'order_items'));
    }

    public function storeUpdateDirectOrder(Request $request)
    {

        $input = $request->all();
        $id    = $input['id'];

        DirectOrderItems::where('order_id', $id)->delete();

        foreach ($input['product_name'] as $i => $product_name) {

            DirectOrderItems::create([
                'order_id'      => $id,
                'hsn_code'      => $request->hsn_code[$i] ?? '',
                'product_name'  => $product_name,
                'quantity'      => $request->quantity[$i] ?? 0,
                'amount'        => $request->amount[$i] ?? 0,
            ]);
        }


        $pdfFileName = 'Invoice_' . $id . '_' . date('Ymd_His') . '.pdf';

        $pdfPath = public_path('uploads/direct_order_invoice/' . $pdfFileName);

        $order_details = DirectOrder::where('id', $id)->first();
        $order_items = DirectOrderItems::where('order_id', $id)->get();

        $pdf = Pdf::loadView(
            'backend.invoice.generate_invoice',
            compact('order_items', 'order_details')
        )
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
            ]);

        $pdf->save($pdfPath);

        DirectOrder::where('id', $id)->update([
            'total_amount' => $request->total_amount,
            'cgst' => $request->total_amount * 0.09,
            'sgst' => $request->total_amount * 0.09,
            'total_tax_amount' => $request->total_amount * 0.18,
            'total_invoice_amount' => ($request->total_amount * 0.18)  +  $request->total_amount,
            'invoice_file' => URL::to('/') . '/uploads/direct_order_invoice/' . $pdfFileName
        ]);

        return redirect()->route('direct-orders')->with('success', 'Invoice generated successfully');
    }

    public function directOrdersAbstract(Request $request)
    {

        $year  = (int) $request->year;
        $month = (int) $request->month;
        $order_status = (int) $request->order_status;
        $abstract_submit =  $request->abstract_submit;

        if (!empty($request)) {

            $query  =  DirectOrder::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)->orderBy('id', 'ASC');
                
            if ($order_status) {
                 $query->where('order_status',$order_status);
            }

            $records = $query->get();


            return view('backend.direct_order.direct_order_list', compact('records'));
        }
    }



    public function storeDirectOrdersAbstract(Request $request)
    {


        $input = $request->all();

        $year  = $input['absract_year'];
        $month = $input['absract_month'];

        $records = DirectOrder::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'ASC')
            ->get();

        $monthName = date('F', mktime(0, 0, 0, $month, 10));

        $pdfFileName = 'Abstract_' . '_' . $monthName . '_' . $year . '.pdf';

        $pdfPath = public_path('uploads/abstract/' . $pdfFileName);

        $company = Company::orderBy('id', 'asc')->first();

        $pdf = Pdf::loadView(
            'backend.invoice.generate_abstract',
            compact('records', 'year', 'month', 'company')
        )->setPaper('A4', 'portrait')
            ->setOptions(['isRemoteEnabled' => true]);

        $pdf->save($pdfPath);

        return response()->download($pdfPath, $pdfFileName);
    }
}
