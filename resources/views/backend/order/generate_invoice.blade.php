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
<b></b> <br>

- <br>
<b>GSTIN:</b>
</p>



<table>
    <tr>
        <th>S.No</th>
        <th>Product Name</th>
        <th>HSN Code</th>
        <th>Quantity</th>
        <th>Amount</th>
    </tr>

    <?php

    foreach($order_items as $key=>$a ) {


        ?>
    <tr>
        <td style="text-align:center;">{{ $key+1 }}</td>
        <td style="text-align:center;">{{ $a->product_name }}</td>
        <td style="text-align:center;">{{ $a->hsn_code }}</td>
        <td style="text-align:center;">{{ $a->quantity }}</td>
        <td class="right">{{ number_format($a->amount,2) }}</td>
    </tr>

    <?php  } ?>


    <tr>
         <td colspan="3" rowspan="1"></td>
        <td class="right"><b>Total Amount</b></td>
        <td class="right" >{{ number_format($order_details->total_amount,2) }}</td>
    </tr>
    <tr>
         <td colspan="3" rowspan="1" ></td>
        <td class="right">CGST @9%</td>
        <td class="right" >{{ number_format($order_details->cgst,2) }}</td>
    </tr>


    <tr>
         <td colspan="3" rowspan="1" ></td>
        <td class="right">SGST @9%</td>
        <td class="right" >{{ number_format($order_details->sgst,2) }}</td>
    </tr>
    <tr>
         <td colspan="3" rowspan="1" ></td>
        <td class="right"><b>Total Tax Amount</b></td>
        <td class="right" >{{ number_format($order_details->total_tax_amount,2) }}</td>
    </tr>
    <tr>
         <td colspan="3"  rowspan="1"></td>
        <td class="right"><b>Total Invoice Amount</b></td>
        <td class="right" ><b>{{ number_format($order_details->total_invoice_amount,2) }}</b></td>
    </tr>


</table>


</body>
</html>


