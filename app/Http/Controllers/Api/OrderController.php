<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\Company;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function placeDirectOrder(Request $request)
    {

        $shop_id = $request->input('shop_id');
        $customer_id = $request->input('customer_id');

        if ($shop_id != '' && $customer_id != '') {

            if ($request->hasFile('order_image')) {
                $file = $request->file('order_image');
                $imageName = 'direct_order_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/direct_order'), $imageName);
                $imageUrl = url('uploads/direct_order/' . $imageName);
            }

            $company_details =  Company::orderBy('id','asc')->first();
            $currentInvoice = $company_details->direct_invoice_no;

            $insertArray = array(
                'shop_id'          => $shop_id,
                'customer_id'      => $customer_id,
                'invoice_no'       => $currentInvoice,
                'image_url'        => $imageUrl,

            );

            $order = DirectOrder::create($insertArray);

            if ($order) {

            $nextInvoice = str_pad((int)$currentInvoice + 1, 3, '0', STR_PAD_LEFT);

                $company_details->update([
                        'direct_invoice_no' => $nextInvoice
                    ]);

                $success_array = array('status' => 'success', 'message' => 'Order placed successfully');
                return response()->json(array($success_array), 200);
            } else {

                $error_array = array('status' => 'error', 'message' => 'Order not placed');
                return response()->json(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }
}
