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
            vertical-align: middle;
        }
        .header-right-cell {
            width: 200px;
            padding: 8px 10px;
            vertical-align: middle;
            text-align: right;
        }
        .company-name {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .company-sub {
            font-size: 10px;
            line-height: 1.7;
        }
        .company-right-text {
            font-size: 10px;
            line-height: 1.8;
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
            font-size: 11px;
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
            font-size: 11px;
        }
        .items-table th {
            background-color: #f0f0f0;
            border: 1px solid #444;
            padding: 5px 7px;
            font-weight: bold;
            font-size: 11px;
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
            font-size: 11px;
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
            font-size: 11px;
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
            font-size: 11px;
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
            font-size: 11px;
            border-bottom: 1px solid #444;
        }
        .rb-inner tr:last-child td {
            border-bottom: none;
        }
        .rb-inner .rb-val {
            text-align: right;
            width: 100px;
        }
        .rb-inner .rb-colon {
            width: 20px;
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
            font-size: 11px;
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
            font-size: 11px;
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
            <td class="header-logo-cell" style="width:80px;">
                <img
                    src="{{ public_path('images/logo.png') }}"
                    alt="Sri Kali Agencies Logo"
                    width="68"
                    height="68"
                    style="display:block;"
                />
                <!--
                    If using raw HTML (not Blade), replace with:
                    <img src="images/logo.png" width="68" height="68" style="display:block;" />

                    DomPDF requires absolute paths for images. Use public_path() in Laravel:
                    <img src="{{ public_path('images/logo.png') }}" width="68" height="68" />
                -->
            </td>

            <!-- COMPANY INFO CENTER -->
            <td class="header-info-cell">
                <div class="company-name">SRI KALI AGENCIES</div>
                <div class="company-sub">
                    438, THIRUVALLUVAR STREET, RAJAPALAYAM<br/>
                    Phone: <strong>6383427538</strong><br/>
                    GSTIN: <strong>33CBSPG5339M1Z5</strong>
                </div>
            </td>

            <!-- COMPANY INFO RIGHT -->
            <td class="header-right-cell">
                <div class="company-right-text">
                    Email: srikaliagencies98@gmail.com<br/>
                    State: <strong>33-Tamil Nadu</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- BILL TO / INVOICE DETAILS               -->
    <!-- ═══════════════════════════════════════ -->
    <table class="bi-table">
        <tr>
            <td style="width:41%; border-right:1px solid #444;">
                <span class="bi-label">Bill To:</span>
                HINDU NADAR URAVIN MURAI, RAJAPALAYAM
            </td>
            <td style="width:50%;">
                <span class="bi-label">Invoice Details:</span>
                No: 72<br/>
                Date: 19-09-2025
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- ITEMS TABLE                             -->
    <!-- ═══════════════════════════════════════ -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:28px;">#</th>
                <th>Item Name</th>
                <th style="width:75px;">HSN/ SAC</th>
                <th class="text-right" style="width:70px;">Quantity</th>
                <th class="text-right" style="width:105px;">Price/ Unit (&#8377;)</th>
                <th class="text-right" style="width:90px;">Amount(&#8377;)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Item Row -->
            <tr>
                <td>1</td>
                <td>INDANE GAS CYLINDER BIG</td>
                <td>457218</td>
                <td class="text-right">29</td>
                <td class="text-right">&#8377; 1,850.00</td>
                <td class="text-right">&#8377; 53,650.00</td>
            </tr>

            <!-- Spacer rows to match original blank space -->
            <tr class="spacer-row">
                <td colspan="6" style="height:90px; border-left:1px solid #444; border-right:1px solid #444; border-top:none; border-bottom:none;"></td>
            </tr>

            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2" style="border:1px solid #444; font-weight:bold;">Total</td>
                <td style="border:1px solid #444;"></td>
                <td class="text-right" style="border:1px solid #444; font-weight:bold;">29</td>
                <td style="border:1px solid #444;"></td>
                <td class="text-right" style="border:1px solid #444; font-weight:bold;">&#8377; 53,650.00</td>
            </tr>
        </tbody>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- SUBTOTAL / TOTAL                        -->
    <!-- ═══════════════════════════════════════ -->
    <table class="summary-table">
        <tr>
            <td class="summary-left-cell">&nbsp;</td>
            <td class="summary-right-outer" style="width:280px; border-left:1px solid #444; padding:0;">
                <table class="summary-inner">
                    <tr>
                        <td>Sub Total</td>
                        <td class="colon-col">:</td>
                        <td class="val-col">&#8377; 53,650.00</td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="colon-col">:</td>
                        <td class="val-col"><strong>&#8377; 53,650.00</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- AMOUNT IN WORDS + RECEIVED / BALANCE    -->
    <!-- ═══════════════════════════════════════ -->
    <table class="words-table">
        <tr>
            <td class="words-left-cell">
                <span class="words-title">Invoice Amount In Words :</span><br/>
                Fifty Three Thousand Six Hundred and Fifty Rupees only
            </td>
            <td class="words-right-cell" style="width:280px; padding:0;">
                <table class="rb-inner">
                    <tr>
                        <td>Received</td>
                        <td class="rb-colon">:</td>
                        <td class="rb-val">&#8377; 0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Balance</strong></td>
                        <td class="rb-colon">:</td>
                        <td class="rb-val"><strong>&#8377; 53,650.00</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ═══════════════════════════════════════ -->
    <!-- TERMS & SIGNATURE                       -->
    <!-- ═══════════════════════════════════════ -->
    <table class="bottom-table">
        <tr>
            <td class="terms-cell">
                <div class="terms-title">Terms And Conditions:</div>
                Thank you for doing business with us.
            </td>
            <td class="sig-cell" style="width:280px;">
                <div class="sig-title">For SRI KALI AGENCIES:</div>
                <div class="sig-space"></div>
                <div class="sig-line">Authorized Signatory</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
