<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .main-box {
            border: 1px solid #000;
            padding: 10px;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        .no-border td {
            border: none !important;
        }

        .no-border {
            border: none !important;
        }

    </style>
</head>
<body>

<div class="main-box">

    <h2 class="center">Tax Invoice</h2>

    <!-- Company Header -->
    <table>
        <tr>
            <td width="15%" class="center">
                <img src="https://erp.yoheshtravels.co.in/backend_assets/images/print_logo.jpg"
                     style="height:70px;width:70px;">
            </td>
            <td width="85%">
                <b>YOHESH TOURS & TRAVELS</b><br>
                NO.8, Balaji Garden, Sriperumbudur - 602105<br>
                Phone: 9940280481<br>
                GSTIN: 33ARCPD5214A2ZR<br>
                State: Tamil Nadu
            </td>
        </tr>
    </table>

    <br>

    <!-- Bill To + Invoice Details -->
    <table>
        <tr>
            <td width="60%">
                <b>Bill To:</b><br>
                {{ $order_details->customer_name ?? '' }}<br>
                {{ $order_details->customer_address ?? '' }}
            </td>
            <td width="40%">
                <b>Invoice Details:</b><br>
                No: {{ $order_details->invoice_no ?? '' }}<br>
                Date: {{ date('d-m-Y') }}
            </td>
        </tr>
    </table>

    <br>

    <!-- Item Table -->
    <table>
        <tr>
            <th width="5%">#</th>
            <th width="35%">Item Name</th>
            <th width="15%">HSN/SAC</th>
            <th width="10%">Quantity</th>
            <th width="15%">Price/Unit (₹)</th>
            <th width="20%">Amount (₹)</th>
        </tr>

        @foreach($order_items as $key => $item)
        <tr>
            <td class="center">{{ $key+1 }}</td>
            <td>{{ $item->product_name }}</td>
            <td class="center">{{ $item->hsn_code }}</td>
            <td class="center">{{ $item->quantity }}</td>
            <td class="right">{{ number_format($item->price,2) }}</td>
            <td class="right">{{ number_format($item->amount,2) }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4"></td>
            <td class="right"><b>Total</b></td>
            <td class="right"><b>{{ number_format($order_details->total_amount,2) }}</b></td>
        </tr>
    </table>

    <br>

    <!-- Right Side Summary (Like Screenshot Box Style) -->
    <table width="50%" align="right">
        <tr>
            <td>Sub Total</td>
            <td class="right">{{ number_format($order_details->total_amount,2) }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td class="right">{{ number_format($order_details->total_invoice_amount,2) }}</td>
        </tr>
        <tr>
            <td colspan="2"><b>Invoice Amount In Words :</b><br>
                {{ $order_details->amount_in_words ?? '' }}
            </td>
        </tr>
        <tr>
            <td>Received</td>
            <td class="right">{{ number_format($order_details->received ?? 0,2) }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td class="right">{{ number_format($order_details->balance ?? 0,2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <br>

    <!-- Terms -->
    <table>
        <tr>
            <td>
                <b>Terms And Conditions:</b><br>
                Thank you for doing business with us.
            </td>
        </tr>
    </table>

    <br>

    <!-- Signature Box -->
    <table width="40%" align="right">
        <tr>
            <td class="center">
                For YOHESH TOURS & TRAVELS<br><br><br>
                _______________________<br>
                Authorized Signatory
            </td>
        </tr>
    </table>

    <div style="clear: both;"></div>

</div>

</body>
</html>
