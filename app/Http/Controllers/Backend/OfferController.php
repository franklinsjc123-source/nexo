<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Offers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;

class OfferController extends Controller
{
    use PermissionCheckTrait;

    public function offers()
    {
        if (!$this->checkPermission('Offers')) {
            return view('unauthorized');
        }


        if (Auth::user()->auth_level == 4) {

            $user_id   =   auth()->id();
            $shop_id   =  Shop::where('user_id', $user_id )->value('id');
            $records   =  Offers::where('shop_id',$shop_id)->orderBy('id', 'desc')->get();

        } else {

            $records   =  Offers::orderBy('id', 'desc')->get();

        }


        return view('backend.offers.list', compact('records'));
    }

    public function addOffer($id = '')
    {
        $records = '';
        if ($id > 0) {
            $records   =  Offers::where('id', $id)->first();
        }

        $shopData       =  Shop::orderBy('shop_name', 'ASC')->get();

        return view('backend.offers.add_edit', compact('records', 'id', 'shopData'));
    }

    public function storeUpdateOffer(Request $request)
    {



        $id                     = $request->id ?? 0;
        $shop_id                = $request->shop_id ?? '';
        $offer_code             = $request->offer_code ?? '';
        $expiry_date            = $request->expiry_date ?? '';
        $discount_percentage    = $request->discount_percentage ?? '';
        $minimum_order_amount   = $request->minimum_order_amount ?? '';


        $data = [
            'shop_id'               => $shop_id,
            'offer_code'            => $offer_code,
            'expiry_date'           => $expiry_date,
            'discount_percentage'   => $discount_percentage,
            'minimum_order_amount'  => $minimum_order_amount,
        ];

        if (empty($id)) {

            $insert = Offers::create($data);

            return redirect()
                ->route('offers')
                ->with(
                    $insert ? 'success' : 'error',
                    $insert ? 'Offer Saved Successfully' : 'Something went wrong!'
                );
        }

        Offers::where('id', $id)->update($data);

        return redirect()->route('offers')->with('success', 'Offer Updated Successfully');
    }
}
