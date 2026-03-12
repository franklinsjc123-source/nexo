<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\Company;

use Razorpay\Api\Api;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Shop;
use App\Models\Invoice;
use App\Models\Address;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{


    public function getAllOrders(Request $request)
    {
        $user_id = $request->input('user_id');

        $orders = Order::with('items')
            ->where('customer_id', $user_id)
            ->orderBy('id', 'DESC')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Records not found'
            ], 400);
        }

        $data = [];

        foreach ($orders as $order) {

            $total_qty = $order->items->sum('qty');

            $data[] = [
                'id'             => $order->id,
                'order_id'       => $order->order_id,
                'total_quantity' => $total_qty,
                'order_status'   => $order->order_status,
                'payment_type'   => $order->payment_type,
                'amount'         => $order->amount,
                'date'           => date('d-m-Y', strtotime($order->created_at))
            ];
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data received successfully',
            'data'    => $data
        ], 200);
    }


    public function getOrderDetails(Request $request)
    {
        $order_id = $request->order_id;

        $order = Order::with(['items.product'])
            ->where('id', $order_id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $address = Address::where('id', $order->delivery_id)
            ->select('name', 'mobile', 'address', 'pincode')
            ->first();

        $products = [];
        $shop_names = [];
        $total_qty = 0;

        foreach ($order->items as $item) {

            $products[] = [
                'product_name' => $item->product->product_name ?? '',
                'qty'          => $item->qty,
                'unit'         =>  $item->unitData->unit_name ?? '',
                'price'        => $item->product_price,
                'total_amount' => $item->price
            ];

            $total_qty += $item->qty;

            $shop = Shop::find($item->shop_id);
            if ($shop) {
                $shop_names[] = $shop->shop_name;
            }
        }

        $shop_names = implode(', ', array_unique($shop_names));

        $data = [
            'order_id'        => $order->order_id,
            'shop_names'      => $shop_names,
            'payment_mode'    => $order->payment_type,
            'order_status'    => $order->order_status,
            'delivery_fee'    => $order->ship_amount,
            'total_quantity'  => $total_qty,
            'total_amount'    => $order->amount,
            'date'            => date('d-m-Y', strtotime($order->created_at)),
            'delivery_address' => $address,
            'products'        => $products
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Order details fetched successfully',
            'data' => $data
        ], 200);
    }




    public function getAllShopOrders(Request $request)
    {
        $user_id = $request->input('user_id');
        $shop_id = Shop::where('user_id', $user_id )->value('id');

        $query = Order::with(['items']);

        if ($shop_id) {
            $query->whereHas('items', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            });
        }

        $orders = $query->orderBy('id', 'DESC')->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Records not found'
            ], 400);
        }

        $data = [];

        foreach ($orders as $order) {

            if ($shop_id) {
                $total_qty = $order->items->where('shop_id', $shop_id)->sum('qty');
            } else {
                $total_qty = $order->items->sum('qty');
            }

            $data[] = [
                'id'             => $order->id,
                'order_id'       => $order->order_id,
                'total_quantity' => $total_qty,
                'order_status'   => $order->order_status,
                'payment_type'   => $order->payment_type,
                'amount'         => $order->amount,
                'date'           => date('d-m-Y', strtotime($order->created_at))
            ];
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data received successfully',
            'data'    => $data
        ], 200);
    }




    public function getShopOrderDetails(Request $request)
    {
        $order_id   = $request->input('order_id');
        $user_id    = $request->input('user_id');
        $shop_id    = Shop::where('user_id', $user_id )->value('id');

        $order = Order::with(['items.product'])
            ->where('id', $order_id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $address = Address::where('id', $order->delivery_id)
            ->select('name', 'mobile', 'address', 'pincode')
            ->first();

        $products = [];
        $shop_names = [];
        $total_qty = 0;

        foreach ($order->items as $item) {

            // shop filter
            if ($shop_id && $item->shop_id != $shop_id) {
                continue;
            }

            $products[] = [
                'product_name' => optional($item->product)->product_name,
                'qty'          => $item->qty,
                'unit'         => $item->unit,
                'price'        => $item->product_price,
                'total_amount' => $item->price
            ];

            $total_qty += $item->qty;

            $shop = Shop::find($item->shop_id);
            if ($shop) {
                $shop_names[] = $shop->shop_name;
            }
        }

        $shop_names = implode(', ', array_unique($shop_names));

        $data = [
            'order_id'        => $order->order_id,
            'shop_names'      => $shop_names,
            'payment_mode'    => $order->payment_type,
            'order_status'    => $order->order_status,
            'delivery_fee'    => $order->ship_amount,
            'total_quantity'  => $total_qty,
            'total_amount'    => $order->amount,
            'date'            => date('d-m-Y', strtotime($order->created_at)),
            'delivery_address' => $address,
            'products'        => $products
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Order details fetched successfully',
            'data' => $data
        ], 200);
    }



    public function placeDirectOrder(Request $request)
    {

        $shop_id = $request->input('shop_id');
        $customer_id = $request->input('customer_id');
        $delivery_id = $request->input('delivery_id');

        if ($shop_id != '' && $customer_id != '') {

            if ($request->hasFile('order_image')) {
                $file = $request->file('order_image');
                $imageName = 'direct_order_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/direct_order'), $imageName);
                $imageUrl = url('uploads/direct_order/' . $imageName);
            }

            $company_details =  Company::orderBy('id', 'asc')->first();
            $currentInvoice = $company_details->direct_invoice_no;

            $insertArray = array(
                'shop_id'          => $shop_id,
                'customer_id'      => $customer_id,
                'delivery_id'      => $delivery_id,
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





    public function placeOrder(Request $request)
    {
        $user_id      = $request->user_id;
        $delivery_id  = $request->delivery_id;
        $payment_type = $request->payment_type;

        $cart = Cart::with('items.product')->where('user_id', $user_id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ]);
        }

        $amount = $cart->total_amount;

        /* ---------------- COD ORDER ---------------- */

        if ($payment_type == 'cod') {

            DB::beginTransaction();

            try {

                $order_number = 'ORD' . time();

                $order = Order::create([
                    'order_id'        => $order_number,
                    'customer_id'     => $user_id,
                    'delivery_id'     => $delivery_id,
                    'order_status'    => 1,
                    'payment_type'    => 'cod',
                    'amount'          => $amount,
                    'ship_amount'     => 0,
                    'payment_status'  => 0,
                    'is_coupon_applied' => 0
                ]);

                /* ---------------- ORDER ITEMS ---------------- */

                foreach ($cart->items as $item) {

                    OrderItems::create([
                        'order_id'      => $order->id,
                        'shop_id'       => $item->shop_id,
                        'product_id'    => $item->product_id,
                        'qty'           => $item->quantity,
                        'unit'          => $item->unit,
                        'product_price' => $item->price,
                        'price'         => $item->total_price
                    ]);
                }

                $order_items = OrderItems::with('product')
                    ->where('order_id', $order->id)
                    ->get();

                $company = Company::first();

                $delivery_address = Address::find($delivery_id);

                /* ---------------- ADMIN INVOICE ---------------- */

                $adminInvoiceName = 'Order_' . $order_number . '.pdf';

                $adminInvoicePath = public_path('uploads/order_invoice/' . $adminInvoiceName);

                $pdf = Pdf::loadView(
                    'backend.invoice.generate_order_invoice',
                    [
                        'order_items' => $order_items,
                        'order_details' => $order,
                        'company' => $company,
                        'delivery_address' => $delivery_address
                    ]
                )->setPaper('A4', 'portrait');

                $pdf->save($adminInvoicePath);

                $order->update([
                    'invoice' => URL::to('/') . '/uploads/order_invoice/' . $adminInvoiceName
                ]);

                /* ---------------- SHOP INVOICE ---------------- */

                $shopItems = $order_items->groupBy('shop_id');

                foreach ($shopItems as $shop_id => $items) {

                    $shop = Shop::find($shop_id);

                    $shopInvoiceName = 'Shop_' . $order_number . '_shop_' . $shop_id . '.pdf';

                    $shopInvoicePath = public_path('uploads/shop_order_invoice/' . $shopInvoiceName);

                    $pdf = Pdf::loadView(
                        'backend.invoice.generate_shop_invoice',
                        [
                            'order_items' => $items,
                            'order_details' => $order,
                            'shop_details' => $shop,
                            'company' => $company,
                            'delivery_address' => $delivery_address
                        ]
                    )->setPaper('A4', 'portrait');

                    $pdf->save($shopInvoicePath);

                    Invoice::create([
                        'order_id'     => $order->id,
                        'shop_id'      => $shop_id,
                        'invoice_path' => URL::to('/') . '/uploads/shop_order_invoice/' . $shopInvoiceName
                    ]);
                }

                /* ---------------- CLEAR CART ---------------- */

                CartItems::where('cart_id', $cart->id)->delete();
                $cart->delete();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order_number
                ]);
            } catch (\Exception $e) {

                DB::rollback();

                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        /* ---------------- RAZORPAY ---------------- */

        if ($payment_type == 'razorpay') {

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $razorpayOrder = $api->order->create([
                'receipt' => Str::random(10),
                'amount' => $amount * 100,
                'currency' => 'INR'
            ]);

            return response()->json([
                'status' => true,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $amount,
                'key' => env('RAZORPAY_KEY')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid payment type'
        ]);
    }




    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Payment verification failed'
            ]);
        }

        $cart = Cart::with('items')->where('user_id', $request->user_id)->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found'
            ]);
        }

        $order_id = 'ORD' . time();
        $pdfFileName = 'Invoice_' . $order_id . '_' . date('Ymd_His') . '.pdf';

        $pdfPath = public_path('uploads/order_invoice/' . $pdfFileName);

        $order = Order::create([
            'order_id' => $order_id,
            'customer_id' => $request->user_id,
            'order_status' => 1,
            'payment_type' => 'razorpay',
            'amount' => $cart->total_amount,
            'ship_amount' => 0,
            'payment_status' => 1,
            'is_coupon_applied' => 0,
            'invoice' => $pdfPath
        ]);

        foreach ($cart->items as $item) {

            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->product_name ?? '',
                'qty' => $item->quantity,
                'unit' => $item->unit,
                'product_price' => $item->price,
                'price' => $item->total_price
            ]);
        }

        $order_details = Order::where('id', $order->id)->first();

        $order_items = OrderItems::with('product')
            ->where('order_id', $order->id)
            ->get();

        $company = Company::orderBy('id', 'asc')->first();

        $pdf = Pdf::loadView(
            'backend.invoice.generate_order_invoice',
            compact('order_items', 'order_details', 'company')
        )
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
            ]);

        $pdf->save($pdfPath);

        CartItems::where('cart_id', $cart->id)->delete();
        $cart->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order->order_id
        ]);
    }
}
