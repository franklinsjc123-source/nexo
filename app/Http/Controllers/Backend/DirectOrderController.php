<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\DirectOrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Company;
use App\Models\Address;

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






        $total_amount =  ($request->total_amount * 0.18)  +  $request->total_amount;
        $amount_in_words =  $this->amountToWords($total_amount);

        DirectOrder::where('id', $id)->update([
            'total_amount' => $request->total_amount,
            'advance_amount' => $request->advance_amount,
            'delivery_amount' => $request->delivery_amount,
            'amount_in_words' => $amount_in_words,
            'cgst' => $request->total_amount * 0.09,
            'sgst' => $request->total_amount * 0.09,
            'total_tax_amount' => $request->total_amount * 0.18,
            'total_invoice_amount' => $total_amount,
            'invoice_file' => URL::to('/') . '/uploads/direct_order_invoice/' . $pdfFileName
        ]);


        $order_details = DirectOrder::where('id', $id)->first();
        $shop_details = Shop::where('id', $order_details->shop_id)->first();
        $order_items = DirectOrderItems::where('order_id', $id)->get();

        $company = Company::orderBy('id', 'asc')->first();
        $delivery_address = Address::where('id', $order_details->delivery_id)->first();



        $pdf = Pdf::loadView(
            'backend.invoice.generate_invoice',
            compact('order_items', 'order_details','company','shop_details','delivery_address')
        )
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
            ]);

        $pdf->save($pdfPath);



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

      function amountToWords($amount)
    {
        $rupees = floor($amount);
        $paise  = round(($amount - $rupees) * 100);

        $words = [
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        ];

        $units = ['', 'thousand', 'lakh', 'crore'];

        $str = [];
        $unitIndex = 0;

        // First 3 digits
        $firstPart = $rupees % 1000;
        $rupees = floor($rupees / 1000);

        if ($firstPart) {
            $str[] = $this->convertThreeDigit($firstPart, $words);
        }

        // Remaining 2-digit groups
        while ($rupees > 0) {
            $number = $rupees % 100;
            $rupees = floor($rupees / 100);

            if ($number) {
                $str[] = $this->convertTwoDigit($number, $words) . ' ' . $units[++$unitIndex];
            } else {
                $unitIndex++;
            }
        }

        $rupeesWords = trim(implode(' ', array_reverse($str)));
        $rupeesWords = ucfirst($rupeesWords) . ' Rupees';

        // Paise
        if ($paise > 0) {
            if ($paise < 21) {
                $paiseWords = ' And ' . ucfirst($words[$paise]) . ' Paise';
            } else {
                $paiseWords = ' And ' .
                    ucfirst($words[floor($paise / 10) * 10] . ' ' . $words[$paise % 10]) .
                    ' Paise';
            }
        } else {
            $paiseWords = '';
        }

        return trim($rupeesWords . $paiseWords . ' Only');
    }

    /* ---- helpers ---- */

    function convertTwoDigit($num, $words)
    {
        if ($num < 21) {
            return $words[$num];
        }
        return $words[floor($num / 10) * 10] . ' ' . $words[$num % 10];
    }

    function convertThreeDigit($num, $words)
    {
        $hundred = floor($num / 100);
        $rest = $num % 100;

        if ($hundred && $rest) {
            return $words[$hundred] . ' hundred ' . $this->convertTwoDigit($rest, $words);
        }

        if ($hundred) {
            return $words[$hundred] . ' hundred';
        }

        return $this->convertTwoDigit($rest, $words);
    }

}
