<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Tax Invoice</title>
    <style>
        /* ===== LARAVEL PDF SAFE CSS (DomPDF / mPDF compatible) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            background: #fff;
        }

        .page {
            width: 92%;
            padding: 30px;
        }

        /* Title */
        .invoice-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            /* text-decoration: underline; */
            margin-bottom: 12px;
        }

        /* All borders */
        .border-all {
            border: 1px solid #444;
        }

        /* Main wrapper table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* HEADER section */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
        }
        .header-logo-cell {
            width: 80px;
            padding: 8px 10px;
            vertical-align: middle;
            border-right: none;
        }
        .header-info-cell {
            padding: 8px 6px;
            vertical-align: center;

        }
        .header-right-cell {
            width: 200px;
            padding: 8px 10px;
            vertical-align: middle;
            text-align: left;
        }
        .company-name {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .company-sub {
            font-size: 13px;
            line-height: 1.7;
        }
        .company-right-text {
            font-size: 13px;
            line-height: 1.8;
            margin-top: 30px!important;
        }

        /* Bill To / Invoice Details */
        .bi-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            border-top: none;
        }
        .bi-table td {
            padding: 5px 10px;
            font-size: 13px;
            vertical-align: top;
            line-height: 1.7;
        }
        .bi-label {
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            border-top: none;
            font-size: 12px;
        }
        .items-table th {
            background-color: #d7d8e9;
            border: 1px solid #444;
            padding: 5px 7px;
            font-weight: bold;
            font-size: 12px;
            text-align: left
        }
        .items-table td {
            border: 1px solid #444;
            padding: 5px 7px;
            vertical-align: top;
        }
        .items-table .spacer-row td {
            height: 90px;
            border-left: 1px solid #444;
            border-right: 1px solid #444;
            border-top: none;
            border-bottom: none;
        }
        .items-table .total-row td {
            font-weight: bold;
            border-top: 1px solid #444;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Summary (SubTotal/Total) */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            border-top: none;
        }
        .summary-table td {
            padding: 4px 7px;
            font-size: 13px;
        }
        .summary-left-cell {
            /* empty left portion */
        }
        .summary-right-outer {
            width: 280px;
            border-left: 1px solid #444;
        }
        .summary-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-inner td {
            padding: 4px 8px;
            border-bottom: 1px solid #444;
            font-size: 13px;
        }
        .summary-inner tr:last-child td {
            border-bottom: none;
        }
        .summary-inner .val-col {
            text-align: right;
            width: 100px;
        }
        .summary-inner .colon-col {
            width: 20px;
        }

        /* Words / Received / Balance */
        .words-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            border-top: none;
        }
        .words-table td {
            padding: 5px 10px;
            font-size: 13px;
            vertical-align: top;
        }
        .words-left-cell {
            line-height: 1.6;
            border-right: 1px solid #444;
        }
        .words-title {
            font-weight: bold;
        }
        .words-right-cell {
            width: 280px;
            padding: 0;
            vertical-align: top;
        }
        .rb-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .rb-inner td {
            padding: 4px 8px;
            font-size: 13px;
            border-bottom: 1px solid #444;
        }
        .rb-inner tr:last-child td {
            border-bottom: none;
        }
        .rb-inner .rb-val {
            text-align: right;
            width: 10px;
        }
        .rb-inner .rb-colon {
            width: 20%;
        }

        /* Bottom: Terms + Signature */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #444;
            border-top: none;
        }
        .bottom-table td {
            padding: 7px 10px;
            font-size: 13px;
            vertical-align: top;
        }
        .terms-cell {
            border-right: 1px solid #444;
            line-height: 1.6;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .sig-cell {
            width: 280px;
            vertical-align: top;
        }
        .sig-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .sig-space {
            height: 55px;
        }
        .sig-line {
            border-top: 1px solid #333;
            text-align: center;
            padding-top: 4px;
            font-size: 13px;
        }

        /* Logo SVG fallback styling */
        .logo-img {
            width: 68px;
            height: 68px;
        }
    </style>
</head>
<body>
<div class="page">

    <!-- ═══════════════════════════════════════ -->
    <!-- TITLE                                   -->
    <!-- ═══════════════════════════════════════ -->
    <div class="invoice-title">Tax Invoice</div>

    <!-- ═══════════════════════════════════════ -->
    <!-- HEADER                                  -->
    <!-- ═══════════════════════════════════════ -->
    <table class="header-table">
        <tr>
            <!-- LOGO: Replace src with your actual logo path e.g. src="{{ public_path('images/logo.png') }}" -->
            <td class="header-logo-cell" style="width:20%;">
                <img
                    src="https://nexoocart.in/uploads/company_logo/company_logo_1771340568_logoo.png"
                    alt="Sri Kali Agencies Logo"
                    width="100"
                    height="100"
                    style="display:block; border-radius:50px"
                />
                <!--
                    If using raw HTML (not Blade), replace with:
                    <img src="images/logo.png" width="68" height="68" style="display:block;" />

                    DomPDF requires absolute paths for images. Use public_path() in Laravel:
                    <img src="{{ public_path('images/logo.png') }}" width="68" height="68" />
                -->
            </td>

            <!-- COMPANY INFO CENTER -->
            <td class="header-info-cell" style="width:50%;">
                <div class="company-name">{{ $company->company_name }}</div>
                <div class="company-sub">
                   {{$company->company_address }}<br/>
                    Phone: <strong>{{$company->phone }}</strong><br/>
                    GSTIN: <strong>{{$company->gst_no }}</strong>
                </div>
            </td>

            <!-- COMPANY INFO RIGHT -->
            <td class="header-right-cell" style="width:30%;">
                <div class="company-right-text ">
                    Email: <strong> {{ $company->email }}</strong><br/>
                    State: <strong>{{ $company->state  }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- BILL TO / INVOICE DETAILS               -->
    <!-- ═══════════════════════════════════════ -->

    <table class="items-table">

          <thead>
            <tr>
                <th>Bill To:</th>
                <th>Invoice Details:<</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td style="width:41%; border-right:1px solid #444;">


                    HINDU NADAR URAVIN MURAI, RAJAPALAYAM
                </td>
                <td style="width:50%;">

                    No: <strong>72</strong><br/>
                    Date: <strong>19-09-2025</strong>
                </td>
            </tr>
        </tbody>

    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- ITEMS TABLE                             -->
    <!-- ═══════════════════════════════════════ -->
    <table class="items-table " style="margin-top:10px">
        <thead>
            <tr>
                <th style="width:28px;">#</th>
                <th>Item Name</th>
                <th style="width:100px;">HSN/ SAC</th>
                <th   style="width:100px; text-align:right">Quantity</th>
                <th style="width:100px; text-align:right">Amount(<span style="font-family: DejaVu Sans, sans-serif;">₹</span>)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Item Row -->

            <?php

            $total_amount = 0;

            foreach($order_items as $key=>$io) {
                    $total_amount  = $total_amount +  $io->amount;
                ?>
            <tr>
                <td>{{  $key+1 }}</td>
                <td class="text-left">{{  $io->product_name }}</td>
                <td class="text-left">{{  $io->hsn_code }}</td>
                <td  class="text-right"  >{{  $io->quantity }}</td>
                <td class="text-right"><span style="font-family: DejaVu Sans, sans-serif;">₹</span> {{  $io->amount }}</td>
            </tr>
            <?php  } ?>


            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2" style="border:1px solid #444; font-weight:bold;">Total</td>
                <td  style="border:1px solid #444;"></td>
                <td class="text-right" style="border:1px solid #444; font-weight:bold;"></td>
                <td class="text-right" style="border:1px solid #444; font-weight:bold;"><span style="font-family: DejaVu Sans, sans-serif;">₹</span> {{ number_format($total_amount,2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- SUBTOTAL / TOTAL                        -->
    <!-- ═══════════════════════════════════════ -->
    <table class="summary-table">
        <tr>
            <td class="summary-left-cell">&nbsp;</td>
            <td class="summary-right-outer" style="width:229px; border-left:1px solid #444; padding:0;">
                <table class="summary-inner">
                    <tr>
                        <td>Sub Total</td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><span style="font-family: DejaVu Sans, sans-serif;">₹</span> {{ number_format($total_amount,2) }}</td>
                    </tr>

                     <tr>
                       <td style="white-space: nowrap;">SGST @ 9%</td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><span style="font-family: DejaVu Sans, sans-serif;">₹</span> {{ number_format($total_amount * 0.09 , 2)}}</td>
                    </tr>


                     <tr>
                        <td style="white-space: nowrap;">CGST  @9%</td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><span style="font-family: DejaVu Sans, sans-serif;">₹</span> {{ number_format($total_amount * 0.09 ,2)}}</td>
                    </tr>
                    <tr>
                        <td style="width:50%" ><strong>Total</strong></td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><strong><span style="font-family: DejaVu Sans, sans-serif;">₹</span>{{ number_format($total_amount +  ( $total_amount*0.18 ),2) }}</strong></td>
                    </tr>

                    <tr>
                      <td colspan="3" style="background-color: #d7d8e9;"><strong>Invoice Amount In Words :</strong></td>
                    </tr>

                     <tr>
                      <td colspan="3" >Fifty Three Thousand Six Hundred and Fifty Rupees only</td>
                    </tr>

                      <tr>
                        <td >Advance</td>
                        <td class="rb-colon">:</td>
                        <td class="rb-val text-right" ><span style="font-family: DejaVu Sans, sans-serif;">₹ {{ number_format ( ($total_amount +  ( $total_amount*0.18 )) * 0.10,2) }} </td>
                    </tr>
                    <tr>
                        <td  style="white-space: nowrap;" >Delivery Charge</td>
                        <td class="rb-colon">:</td>
                        <td class="rb-val text-right"><span style="font-family: DejaVu Sans, sans-serif;">₹ 100 </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>



      <table class="items-table" style="margin-top:10px">

        <thead>
            <tr>
                <th>Terms And Conditions:</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:41%; border-right:1px solid #444;">
                    Thank you for doing business with us.
                </td>
            </tr>
        </tbody>

    </table>




    <table class="bottom-table">

        <tr>
            <td></td>
            <td  style="background-color: #d7d8e9;"><strong>For {{ $company->company_name }}</strong></td>
        </tr>
        <tr>
            <td class="terms-cell">

            </td>
            <td class="sig-cell" style="width:280px;">
                <br>
                <br>
                <br>
                <br>
                <div ><center>Authorized Signatory</center></div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
