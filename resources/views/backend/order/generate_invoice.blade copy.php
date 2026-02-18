<!DOCTYPE html>
<html>
<head>
    <style>


        body { font-family: DejaVu Sans; font-size: 12px; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        .no-border td { border: none; }
        .right { text-align: right; }

          .company-address {
    width: 100%;
    max-width: 40%;
    white-space: normal;
    word-wrap: break-word;
    line-height: 1.6;
}
    </style>
</head>
<body>

    <table width="100%" style="margin-bottom:15px; border:none !important; border-collapse:collapse;">
    <tr style="border:none !important;"><td width="100%" style="border:none !important;" class="right" colspan="2"><p><b> PAN No: ARCPD5214A  </b></p>
<p> <b>GST No: 33ARCPD5214A2ZR</b>  </p></td></tr>
    <tr>
        <!-- Logo -->
        <td width="20%" style="border:none;">
            <img src="https://erp.yoheshtravels.co.in/backend_assets/images/print_logo.jpg" style="height :100px; width:100px">
        </td>

        <!-- Company Details -->
        <td width="90%" style="border:none; text-align:center;">
            <h2 style="margin:0;">   YOHESH TOURS & TRAVELS </h2>
            <p style="margin:5px 0;">
               <b> NO.8, Balaji Garden, Sriperumbudur - 602 105 </b> <br>

           <b>  Cell : 9940280481, 9942416262 </b> </p>
        </td>
    </tr>
</table>

{{-- <h2 class="center"> YOGESH TRAVELS </h2>
<p class="center">
   NO.8, BALAJI GARDEN , SRIPERUMBUDUR, KATCHIPEDU<br>
   KANCHIPURAM - 602105
</p> --}}

<hr>


<p  class="company-address">

 To:<br>
<b>{{ $company->full_name }}</b> <br>
{{ $company->company_address }}
- {{ $company->pincode   }}<br>
<b>GSTIN:</b> {{ $company->gst_no }}
</p>

<p><b> Hire Charges For the Month of  {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}  </b></p>
<p> <b>For  {{ $route_name }}   Route</b>  </p>


<table>
    <tr>
        <th>S.No</th>
        <th>Vehicle No</th>
        <th>Vehicle Type</th>
        <th>Route Name</th>
        <th>Hire Charges</th>
        <th>Amount</th>
    </tr>

    <?php foreach($vehicleAmount as $key=>$a ) {  ?>
    <tr>
        <td style="text-align:center;">{{ $key+1 }}</td>
        <td style="text-align:center;">{{ $a->vehicleData->vehicle_no }}</td>
        <td style="text-align:center;">{{ $a->vehicleData->modelData->vehicle_model_name }}</td>
        <td style="text-align:center;"> {{ $route_name }}</td>
        <td class="right">{{ number_format($a->hire_charges,2) }}</td>
        <td class="right">{{ number_format($a->amount,2) }}</td>
    </tr>

    <?php  } ?>
    <tr><th colspan="6">Extra KM Details</th></tr>
    <tr>
        <th>S.No</th>
        <th>Vehicle No</th>
        <th>Extra KM</th>
        <th>Rate</th>
        <th  colspan="2">Amount</th>
    </tr>
    <?php foreach($extraKm as $key=>$ext ) {  ?>
    <tr>
        <td style="text-align:center;">{{ $key+1 }}</td>
        <td style="text-align:center;">{{ $ext->vehicleData->vehicle_no }}</td>
        <td style="text-align:center;">{{ $ext->extra_km }}</td>
        <td style="text-align:center;"> {{ $ext->extra_km_rate }}</td>
        <td   colspan="2" class="right">{{ number_format($ext->extra_km_amount,2) }}</td>
    </tr>

    <?php  } ?>

       <?php if($billExtras->isNotEmpty()){ ?>
         <tr>

            <td colspan="6" style="text-align:center;" ><b>Other Bills</b> </td>

        </tr>

         <?php foreach($billExtras as $key=>$e ) {  ?>
        <tr>

        <td  colspan="4"  class="right"> {{ $e->name }}</td>
        <td class="right" colspan="2">{{ number_format($e->amount,2) }}</td>
    </tr>

    <?php  } ?>


    <?php  } ?>

    <tr>
         <td colspan="3" rowspan="1"></td>
        <td class="right"><b>Total Amount</b></td>
        <td class="right" colspan="2">{{ number_format($bill->total_amount,2) }}</td>
    </tr>
    {{-- <tr>
        <td class="right">CGST @9%</td>
        <td class="right" colspan="2">{{ number_format($bill->cgst,2) }}</td>
    </tr> --}}
    {{-- <tr>
        <td class="right">SGST @9%</td>
        <td class="right" colspan="2">{{ number_format($bill->sgst,2) }}</td>
    </tr>
    <tr>
        <td class="right"><b>Total Tax Amount</b></td>
        <td class="right" colspan="2">{{ number_format($bill->total_tax_amount,2) }}</td>
    </tr>
    <tr>
        <td class="right"><b>Total Invoice Amount</b></td>
        <td class="right" colspan="2"><b>{{ number_format($bill->total_invoice_amount,2) }}</b></td>
    </tr> --}}
    {{-- <tr><td colspan="6"><b>GST Amount in Words :</b> {{ $bill->tax_amount_words }}</td></tr> --}}
    <tr><td colspan="6" ><b>Total Amount in Words :</b> {{ $bill->invoice_amount_words }}</td></tr>

</table>

<br>

<!-- <table>
    <tr>
        <td class="right"><b>Total Amount</b></td>
        <td class="right">{{ number_format($bill->total_amount,2) }}</td>
    </tr>
    <tr>
        <td class="right">CGST @9%</td>
        <td class="right">{{ number_format($bill->cgst,2) }}</td>
    </tr>
    <tr>
        <td class="right">SGST @9%</td>
        <td class="right">{{ number_format($bill->sgst,2) }}</td>
    </tr>
    <tr>
        <td class="right"><b>Total Tax Amount</b></td>
        <td class="right">{{ number_format($bill->total_tax_amount,2) }}</td>
    </tr>
    <tr>
        <td class="right"><b>Total Invoice Amount</b></td>
        <td class="right"><b>{{ number_format($bill->total_invoice_amount,2) }}</b></td>
    </tr>
</table> -->

</body>
</html>








<!DOCTYPE html>
<html>
<head>
    <style>
        .below-table { font-family: DejaVu Sans; font-size: 11px; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px; }
        .no-border td { border: none; }
        .right { text-align: right; }
    </style>
</head>
<body class="below-table">

    <table width="100%" style="margin-top:-20px; border:none !important; border-collapse:collapse;">
        <tr style="border:none !important;">

            <td width="20%" style="border:none;">
                <img src="https://erp.yoheshtravels.co.in/backend_assets/images/print_logo.jpg" style="height :80px; width:80px">
            </td>

            <td width="50%" style="border:none; text-align:center;">
                <h2 style="margin:0;"> YOHESH TOURS & TRAVELS</h2>
                <p style="margin:5px 0;">
                <b> NO.8, Balaji Garden, Sriperumbudur - 602 105 </b> <br>

                <b>  Cell : 9940280481, 9942416262 </b> </p>
            </td>
            <td width="20%" style="border:none !important;" class="right" colspan="2">
                <b> PAN No: ARCPD5214A  </b><br>
                <b>GST No: 33ARCPD5214A2ZR</b>
            </td>
        </tr>

</table>
<hr>

 To:<br>
<b>{{ $company->full_name }}</b> <br>
{{ $company->company_address }}
- {{ $company->pincode   }}<br>
<b>GSTIN:</b> {{ $company->gst_no }}




<table class="no-border">
    <tr>
        <td width="25%">  <b>Route :  {{ $route_name }}   </b></td>
        <td width="25%"><b >Vehicle No :  {{ $route_data->vehicles->vehicle_no }}   </b></td>
        <td width="25%"> <b>Shift :  {{  $route_data->shifts->shift_name }}   </b></td>
        <td width="25%"> <b >Vehicle type :  {{  $route_data->vehicles->seat . ' SEAT '.  $route_data->vehicles->modelData->vehicle_model_name   }}   </b></td>
    </tr>

</table>

<br>
<table>
    <tr>
        <th>Date</th>
        <th>Day</th>
        {{-- <th>Vehicle No</th> --}}
        <th>Starting time</th>
        <th>Starting Kms</th>
        <th>Closing Time</th>
        <th>Closing Kms</th>
        <th>Running Kms</th>
        <th>Remarks</th>
    </tr>

    <?php foreach($log_datas as $key=>$d ) {  ?>
    <tr>
        <td style="text-align:center;">   {{ \Carbon\Carbon::parse($d->log_date)->format('d-m-Y') }}</td>
        <td style="text-align:center;">
            {{ \Carbon\Carbon::parse($d->log_date)->format('l') }}
        </td>
        {{-- <td style="text-align:center;">{{ $d->vehicles->vehicle_no ?? '' }}</td> --}}
        <td style="text-align:center;"> {{ $d->starting_time ? \Carbon\Carbon::parse($d->starting_time)->format('h:i A') : '' }}</td>
        <td style="text-align:center;">{{ $d->starting_km }}</td>
        <td style="text-align:center;">{{ $d->closing_time ? \Carbon\Carbon::parse($d->closing_time)->format('h:i A') : '' }}</td>
        <td style="text-align:center;">{{ $d->closing_km }}</td>
        <td style="text-align:center;">{{ $d->running_km }}</td>
        <td>{{ $d->remarks }}</td>
    </tr>
    <?php  } ?>

</table>

<br>


</body>
</html>

