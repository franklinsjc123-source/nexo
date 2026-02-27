
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px; }
        .no-border td { border: none; }
        .right { text-align: right; }
        .no-border,
        .no-border th,
        .no-border td {
            border: none !important;
        }


    </style>
</head>
<body>

     <table width="100%" style=" margin-top:-20px; border:none !important; border-collapse:collapse;">
        <tr style="border:none !important;">

            <td width="10%" style="border:none;">
                <img  src="{{ public_path('backend_assets/images/invoice.jpg') }}" style="  border-radius: 50px; height :80px; width:80px">
            </td>

            <td width="60%" style="border:none; text-align:center;">
                <h2 style="margin:0;"> NEXOCART</h2>
                <p style="margin:5px 0;">
                <b>{{ $company->company_address }} , <br>{{  $company->state }}.</b> <br>
                <b>  Email :  {{ $company->email }} </b> </p>



            </td>
            <td width="35%" style="border:none !important;" style="float:left" colspan="2">
                <b>GST No: {{ $company->gst_no }}</b><br>
                <b>  Mobile :  {{ $company->phone }} </b> </p>

            </td>
        </tr>

</table>

<hr>


   <h3><b>Direct Order Abstract  For the Month of {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}  </b></h3>

<br>



<table>
    <tr>
        <th>S.No</th>
        <th>Order Date</th>
        <th>Customer Name </th>
        <th>Shop Name</th>
        <th>Amount</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>Total Amount</th>

    </tr>

    <?php

        $total_invoice_amount = 0;
        $total_cgst = 0;
        $total_sgst = 0;
        $total_amount = 0;

        foreach($records as $key=>$d ) {

            $total_cgst = $d->cgst ;
            $total_sgst = $d->sgst ;
            $total_amount = $d->total_amount ;
            $total_invoice_amount = $d->total_invoice_amount ;

    ?>
    <tr>
        <td> {{ $key +1 }}</td>

        <td style="text-align:center;">
            {{  date('d-m-Y', strtotime($d->created_at)) }}
        </td>

        <td style="text-align:center;">
            {{ $d->userData->name ?? 0 }}
        </td>

        <td style="text-align:center;">
            {{ $d->shopData->shop_name ?? 0 }}
        </td>

        <td style="text-align:center;">
            {{ $d->total_amount ?? 0 }}
        </td>

        <td style="text-align:center;">
            {{ $d->cgst ?? 0 }}
        </td>

        <td style="text-align:center;">
            {{ $d->sgst ?? 0 }}
        </td>

         <td style="text-align:center;">
            {{ $d->total_invoice_amount ?? 0 }}
        </td>


    </tr>

     <?php  } ?>

    <tr>
        <td style="text-align:center;" colspan="4"><b>Total</b></td>
        <td style="text-align:center;" ><b>{{ number_format($total_amount ,2) }}</b></td>
        <td style="text-align:center;" ><b>{{ number_format($total_cgst ,2) }}</b></td>
        <td style="text-align:center;" ><b>{{ number_format($total_sgst ,2) }}</b></td>
        <td style="text-align:center;" ><b>{{ number_format($total_invoice_amount ,2) }}</b></td>

    </tr>

</table>

</body>
</html>

