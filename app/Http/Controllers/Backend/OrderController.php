<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DirectOrder;
use App\Models\DirectOrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class OrderController extends Controller
{
    use PermissionCheckTrait;

    public function orders()
    {
        if (!$this->checkPermission('Orders')) {
            return view('unauthorized');
        }

        $records   =  Order::orderBy('id', 'DESC')->get();
        return view('backend.order.list', compact('records'));
    }


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

        return view('backend.order.direct_order_list', compact('records'));
    }


    public function updateOrderStatus(Request $request)
    {
        DirectOrder::where('id', $request->order_id)
            ->update(['status' => $request->status]);

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

        return view('backend.order.add_edit', compact('record', 'order_items'));
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
            'backend.order.generate_invoice',
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
}
