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
use App\Models\Category;
use App\Models\DeliveryPerson;
use App\Models\PinCode;
use App\Models\User;
use App\Models\OffersUsed;
use App\Models\Offers;
use App\Models\DeclineOrder;

use Carbon\Carbon;


use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Google_Client;

class OrderController extends Controller
{


    public function getAllOrders(Request $request)
    {
        $user_id = $request->input('user_id');

        $orders = Order::where('customer_id', $user_id)
            ->with('items.shopData')
            ->select(
                'id',
                'order_id',
                'amount',
                'ship_amount',
                'order_status',
                'payment_type',
                'created_at'
            )
            ->get();

        $directOrders = DirectOrder::where('customer_id', $user_id)
            ->with('shopData')
            ->select(
                'id',
                'invoice_no',
                'total_invoice_amount',
                'order_status',
                'image_url',
                'created_at'
            )
            ->get();

        $allOrders = $orders->concat($directOrders)
            ->sortByDesc('created_at')
            ->values();

        if ($allOrders->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Records not found'
            ], 400);
        }

        $data = $allOrders->map(function ($order) {

            if (isset($order->order_id)) {

                $shopNames = collect($order->items)
                    ->pluck('shopData.shop_name')
                    ->filter()
                    ->unique()
                    ->values()
                    ->implode(', ');

                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'shop_name' => $shopNames,
                    'amount' => $order->amount + $order->ship_amount,
                    'order_status' => $order->order_status,
                    'payment_type' => $order->payment_type,
                    'image_url' => '',
                    'order_type' => 'cart_order',
                    'date' => date('d-m-Y', strtotime($order->created_at)),
                ];
            } else {

                $shopName = optional($order->shopData)->shop_name;

                return [
                    'id' => $order->id,
                    'order_id' => $order->invoice_no,
                    'shop_name' => $shopName,
                    'amount' => $order->total_invoice_amount,
                    'order_status' => $order->order_status,
                    'payment_type' => 'Cash on Delivery',
                    'image_url' => $order->image_url,
                    'order_type' => 'direct_order',
                    'date' => date('d-m-Y', strtotime($order->created_at)),
                ];
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $data
        ]);
    }

    public function getOrderDetails(Request $request)
    {
        $order_id = $request->order_id;
        $deliver_person_id = $request->deliver_person_id;

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
            ->select('name', 'mobile', 'address', 'pincode', 'landmark')
            ->first();

        $products = [];
        $shop_names = [];
        $total_qty = 0;
        $sub_total = 0;

        $declinedOrderIds = DeclineOrder::where('delivery_person_id', $deliver_person_id)
            ->pluck('order_id')
            ->toArray();

        foreach ($order->items as $item) {

            $products[] = [
                'product_name' => $item->product->product_name ?? '',
                'qty'          => $item->qty,
                'unit'         =>  $item->unitData->unit_name ?? '',
                'price'        => $item->product_price,
                'total_amount' => $item->price
            ];

            $total_qty += $item->qty;
            $sub_total +=  $item->price;

            $shop = Shop::find($item->shop_id);
            if ($shop) {
                $shop_names[] = $shop->shop_name;
            }
        }

        $shop_names = implode(', ', array_unique($shop_names));

        $data = [
            'order_id'        => $order->order_id,
            'is_declined' => $order->deliver_person_id == $deliver_person_id ? 2 : (in_array($order->id, $declinedOrderIds) ? 1 : 0),
            'shop_names'      => $shop_names,
            'payment_mode'    => $order->payment_type,
            'order_status'    => $order->order_status,
            'sub_total'       => $sub_total,
            'discount'        => $order->coupon_applied_amount,
            'delivery_fee'    => $order->ship_amount,
            'total_quantity'  => $total_qty,
            'total_amount'    => $order->amount + $order->ship_amount,
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

        $shop_id = Shop::where('user_id', $user_id)->value('id');

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
                $items = $order->items->where('shop_id', $shop_id);

                $total_product_count = $items->groupBy('product_id')->count();

                $total_qty = $items->sum('qty');
                $total_amount = $items->sum('price');
            } else {
                $total_qty = $order->items->sum('qty');
                $total_amount = $order->items->sum('price');
            }

            $data[] = [
                'id'             => $order->id,
                'order_id'       => $order->order_id,
                'total_quantity' => $total_product_count ? $total_product_count : $total_qty,
                'order_status'   => $order->order_status,
                'payment_type'   => $order->payment_type,
                'amount'         => $total_amount,
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
        $shop_id    = Shop::where('user_id', $user_id)->value('id');

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
            ->select('name', 'mobile', 'address', 'pincode', 'landmark')
            ->first();

        $products = [];
        $shop_names = [];
        $total_qty = 0;
        $total_amount = 0;

        foreach ($order->items as $item) {

            if ($shop_id && $item->shop_id != $shop_id) {
                continue;
            }

            $products[] = [
                'product_name' => optional($item->product)->product_name,
                'qty'          => $item->qty,
                'unit'         => $item->unitData->unit_name ?? '',
                'price'        => $item->product_price,
                'total_amount' => $item->price
            ];

            $total_qty += $item->qty;
            $total_amount += $item->price;


            $shop = Shop::find($item->shop_id);
            if ($shop) {
                $shop_names[] = $shop->shop_name;
            }
        }

        $shop_names = implode(', ', array_unique($shop_names));

        $discount_amount = 0;
        if (!empty($order->offer_applied_ids)) {

            $offer_ids_array = explode(',', $order->offer_applied_ids);

            $offer_used = OffersUsed::whereIn('offer_id', $offer_ids_array)->get();

            $offers = Offers::whereIn('id', $offer_ids_array)->get()->keyBy('id');

            foreach ($offer_used as $offer) {

                $offerDetails = $offers[$offer->offer_id] ?? null;

                if (!$offerDetails) {
                    continue;
                }

                if ($offerDetails->shop_id == $shop_id) {

                    if ($total_amount >= $offerDetails->minimum_order_amount) {

                        $discount_percentage = $offerDetails->discount_percentage ?? 0;

                        $discount_amount += ($total_amount * $discount_percentage) / 100;
                    }
                }
            }
        }



        $final_shop_total = $total_amount - $discount_amount;



        $data = [
            'order_id'        => $order->order_id,
            'shop_names'      => $shop_names,
            'payment_mode'    => $order->payment_type,
            'order_status'    => $order->order_status,
            'delivery_fee'    => $order->ship_amount,
            'total_quantity'  => $total_qty,
            'discount'        => $discount_amount,
            'total_amount'    => $final_shop_total,
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

    public function changeOrderStatus(Request $request)
    {
        $order_id     = $request->order_id;
        $order_status = $request->order_status;
        $shop_id      = $request->shop_id;

        $now = Carbon::now('Asia/Kolkata');

        if (!$order_id || $order_status === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ID and Order Status are required'
            ], 400);
        }

        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        if ($order_status == 2) {
            $order->order_status = 2;
            $order->delivery_date = $now;
        } elseif ($order_status == 3) {
            $order->order_status = 3;
            $order->cancel_date = $now;
        } elseif ($order_status == 4) {

            Invoice::where('order_id', $order_id)
                ->where('shop_id', $shop_id)
                ->update(['is_dispatched' => 1]);

            $total_shops = Invoice::where('order_id', $order_id)->count();

            $dispatched_shops = Invoice::where('order_id', $order_id)
                ->where('is_dispatched', 1)
                ->count();

            if ($total_shops == $dispatched_shops) {
                $order->order_status = 4;
                $order->shipped_date = $now;
            }
        }

        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully'
        ], 200);
    }




    public function OrderCancel(Request $request)
    {
        $order_id     = $request->order_id;

        if (!$order_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ID is required'
            ], 400);
        }

        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->order_status = 3;
        $order->save();


        $order_items = OrderItems::with('product')
            ->where('order_id', $order->id)
            ->get();



        $shopItems = $order_items->groupBy('shop_id');

        foreach ($shopItems as $shop_id => $items) {

            $shop = Shop::find($shop_id);

            $shop_user_id = $shop->user_id;

            $message = "Order #" . $order->order_id . " has been cancelled by customer.";

            $this->sendNotificationForShops($shop_user_id, 'Order Cancelled - NexOcart', $message);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully'
        ], 200);
    }









    public function placeDirectOrder(Request $request)
    {

        $shop_id = $request->input('shop_id');
        $customer_id = $request->input('customer_id');
        $delivery_id = $request->input('delivery_id');

        $now = Carbon::now('Asia/Kolkata')->format('d-m-Y h:i A');

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
                'created_at'       =>  $now,

            );

            $order = DirectOrder::create($insertArray);

            if ($order) {

                $currentInvoice = $company_details->direct_invoice_no ?? 'NCD-0000';

                $number = (int) str_replace('NCD-', '', $currentInvoice);

                $nextNumber = $number + 1;

                $nextInvoice = 'NCD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

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
        $user_id            = $request->user_id;
        $delivery_id        = $request->delivery_id;
        $payment_type       = $request->payment_type;
        $offer_applied_ids  = $request->offer_ids;
        $discount           = $request->discount ?  $request->discount : 0;


        $cart = Cart::with('items.product')->where('user_id', $user_id)->first();



        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ]);
        }



        $categoryTotals = [];

        foreach ($cart->items as $item) {

            $category_id = $item->product->category;


            if (!isset($categoryTotals[$category_id])) {
                $categoryTotals[$category_id] = 0;
            }

            $categoryTotals[$category_id] += $item->total_price;
        }

        foreach ($categoryTotals as $category_id => $total) {

            $category = Category::find($category_id);

            if ($category && $total < $category->min_order_value) {

                return response()->json([
                    'status' => false,
                    'message' => $category->category_name .
                        " minimum order amount is ₹" . $category->min_order_value
                ], 400);
            }
        }

        $delivery_charge = 0;

        foreach ($categoryTotals as $category_id => $total) {

            $category = Category::find($category_id);

            if (!$category) {
                continue;
            }

            $category_name = strtolower($category->category_name);

            if ($category_name == 'grocery') {

                if ($total > 1000) {
                    $delivery_charge += ($total * 8) / 100;
                } else {
                    $delivery_charge += ($total * 10) / 100;
                }
            } elseif ($category_name == 'medicine') {

                if ($total > 500) {
                    $delivery_charge += ($total * 8) / 100;
                } else {
                    $delivery_charge += 50;
                }
            } elseif (in_array($category_name, ['fruits', 'vegetables', 'hotel', 'bakery'])) {

                $delivery_charge += 50;
            } else {

                $delivery_charge += 50;
            }
        }

        $delivery_address = Address::where('id', $delivery_id)->first();

        $pincode_charge = 0;

        if ($delivery_address) {

            $pincode_charge = PinCode::where('pincode', $delivery_address->pincode)->value('delivery_charge');
        }
        $delivery_charge = round($delivery_charge + $pincode_charge);



        $amount = $cart->total_amount - $discount;

        $amount_in_words =  $this->amountToWords($amount);


        if ($payment_type == 'cod') {

            DB::beginTransaction();

            try {

                $order_number = 'ORD' . time();

                $order = Order::create([
                    'order_id'              => $order_number,
                    'customer_id'           => $user_id,
                    'delivery_id'           => $delivery_id,
                    'order_status'          => 1,
                    'payment_type'          => 'cod',
                    'amount'                => $amount,
                    'ship_amount'           => $delivery_charge,
                    'payment_status'        => 0,
                    'offer_ids'             => $offer_applied_ids,
                    'is_coupon_applied'     => $discount  > 0 ? 1 : 0,
                    'coupon_applied_amount' => $discount ?  $discount : 0,
                    'amount_in_words'       => $amount_in_words

                ]);


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


                $currentInvoice = $company->invoice_no ?? 'NCO-0000';

                $number = (int) str_replace('NCO-', '', $currentInvoice);

                $nextNumber = $number + 1;

                $nextInvoice = 'NCO-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                $company->update([
                    'invoice_no' => $nextInvoice
                ]);



                $delivery_address = Address::find($delivery_id);


                $adminInvoiceName = 'Order_' . $order_number . date('Ymd_His') . '.pdf';

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


                $shopItems = $order_items->groupBy('shop_id');

                foreach ($shopItems as $shop_id => $items) {

                    $shop = Shop::find($shop_id);

                    $shop_user_id = $shop->user_id;



                    $message = "New order received from Order #" . $order_number;

                    $this->sendNotificationForShops($shop_user_id, 'New Order - NexoCart', $message);

                    $shop_total = $items->sum('price');

                    $discount_amount = 0;

                    $offer_used = OffersUsed::where('cart_id', $cart->id)->get();

                    // print_r( $offer_used);exit;

                    if ($offer_used->isNotEmpty()) {

                        $offer_ids_array = $offer_used->pluck('offer_id')->toArray();

                        $offers = Offers::whereIn('id', $offer_ids_array)->get()->keyBy('id');

                        foreach ($offer_used as $offer) {

                            $offerDetails = $offers[$offer->offer_id] ?? null;

                            if (!$offerDetails) {
                                continue;
                            }

                            if ($offerDetails->shop_id == $shop_id) {

                                if ($shop_total >= $offerDetails->minimum_order_amount) {

                                    $discount_percentage = $offerDetails->discount_percentage ?? 0;

                                    $discount_amount += ($shop_total * $discount_percentage) / 100;
                                }
                            }
                        }
                    }

                    $final_shop_total = $shop_total - $discount_amount;


                    $shop_amount_words = $this->amountToWords($final_shop_total);

                    $shopInvoiceName = 'Shop_' . $order_number . '_shop_' . $shop_id . date('Ymd_His') . '.pdf';

                    $shopInvoicePath = public_path('uploads/shop_order_invoice/' . $shopInvoiceName);

                    $pdf = Pdf::loadView(
                        'backend.invoice.generate_shop_invoice',
                        [
                            'order_items' => $items,
                            'order_details' => $order,
                            'shop_details' => $shop,
                            'company' => $company,
                            'delivery_address' => $delivery_address,
                            'shop_amount_words' => $shop_amount_words,
                            'discount_amount' => $discount_amount,
                        ]
                    )->setPaper('A4', 'portrait');

                    $pdf->save($shopInvoicePath);

                    Invoice::create([
                        'order_id'              => $order->id,
                        'shop_id'               => $shop_id,
                        'final_shop_total'      => $final_shop_total,
                        'shop_discount'         => $discount_amount,
                        'invoice_path'          => URL::to('/') . '/uploads/shop_order_invoice/' . $shopInvoiceName
                    ]);
                }


                CartItems::where('cart_id', $cart->id)->delete();
                $cart->delete();

                DB::commit();

                $deliery_persons = DeliveryPerson::whereNotNull('device_id')->get();
                $message = "New order ready for pickup and delivery.";

                foreach ($deliery_persons as $delivery_person) {

                    $this->sendNotificationforDeliveryPersons($delivery_person->device_id, 'New Order - NexoCart', $message);
                }


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

        $user_id     = $request->user_id;
        $delivery_id = $request->delivery_id;
        $discount    = $request->discount ?? 0;
        $offer_ids   = $request->offer_ids;

        $cart = Cart::with('items.product')->where('user_id', $user_id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ]);
        }



        $categoryTotals = [];

        foreach ($cart->items as $item) {
            $category_id = $item->product->category;

            if (!isset($categoryTotals[$category_id])) {
                $categoryTotals[$category_id] = 0;
            }

            $categoryTotals[$category_id] += $item->total_price;
        }

        foreach ($categoryTotals as $category_id => $total) {

            $category = Category::find($category_id);

            if ($category && $total < $category->min_order_value) {
                return response()->json([
                    'status' => false,
                    'message' => $category->category_name .
                        " minimum order amount is ₹" . $category->min_order_value
                ], 400);
            }
        }



        $delivery_charge = 0;

        foreach ($categoryTotals as $category_id => $total) {

            $category = Category::find($category_id);
            if (!$category) continue;

            $name = strtolower($category->category_name);

            if ($name == 'grocery') {
                $delivery_charge += ($total > 1000) ? ($total * 8) / 100 : ($total * 10) / 100;
            } elseif ($name == 'medicine') {
                $delivery_charge += ($total > 500) ? ($total * 8) / 100 : 50;
            } else {
                $delivery_charge += 50;
            }
        }

        $address = Address::find($delivery_id);
        $pincode_charge = 0;

        if ($address) {
            $pincode_charge = PinCode::where('pincode', $address->pincode)->value('delivery_charge');
        }

        $delivery_charge = round($delivery_charge + $pincode_charge);

        $amount = $cart->total_amount - $discount;
        $amount_words = $this->amountToWords($amount);



        DB::beginTransaction();

        try {

            $order_number = 'ORD' . time();

            $order = Order::create([
                'order_id'              => $order_number,
                'customer_id'           => $user_id,
                'delivery_id'           => $delivery_id,
                'order_status'          => 1,
                'payment_type'          => 'razorpay',
                'amount'                => $amount,
                'ship_amount'           => $delivery_charge,
                'payment_status'        => 1, // ✅ PAID
                'offer_ids'             => $offer_ids,
                'is_coupon_applied'     => $discount > 0 ? 1 : 0,
                'coupon_applied_amount' => $discount,
                'amount_in_words'       => $amount_words
            ]);

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

            $invoiceName = 'Order_' . $order_number . date('Ymd_His') . '.pdf';

            $pdf = Pdf::loadView(
                'backend.invoice.generate_order_invoice',
                compact('order_items', 'order', 'company', 'delivery_address')
            )->setPaper('A4', 'portrait');

            $pdf->save(public_path('uploads/order_invoice/' . $invoiceName));

            $order->update([
                'invoice' => URL::to('/') . '/uploads/order_invoice/' . $invoiceName
            ]);



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


    public function sendNotificationForShops($userid, $title, $msg)
    {

        $firebaseToken = User::where('id', $userid)->first('token_id');


        $NotificationData = ['title' => $title, 'body'  => $msg , 'shop_id' => (string)$userid];
        $titles           = ['title' => $title, 'body'  => $msg];
        $data             = [
            'message' => [
                'token' => $firebaseToken['token_id'],
                'notification' => $titles,
                'data' => $NotificationData
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/nexocart-3f870/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            return response()->json(['error' => $responseData['error']], 500);
        }
        return response()->json(['response' => $responseData]);
    }





    public function sendNotificationforDeliveryPersons($device_id, $title, $msg)
    {

        $NotificationData = ['title' => $title, 'body'  => $msg];
        $titles           = ['title' => $title, 'body'  => $msg];

        $data             = [
            'message' => [
                'token' => $device_id,
                'notification' => $titles,
                'data' => $NotificationData
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: Bearer ' . $this->getAccessTokenDelivery(),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/nxodriver/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $responseData = json_decode($response, true);
        if (isset($responseData['error'])) {
            return response()->json(['error' => $responseData['error']], 500);
        }
        return response()->json(['response' => $responseData]);
    }


      public function getAccessToken()
    {
        $credentialsPath = storage_path('app/firebase-service-account.json');
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }




    public function getAccessTokenDelivery()
    {
        $credentialsPath = storage_path('app/firebase-service-account-delivery.json');
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
}
