<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Invoice</title>
    <style>
        @page {
            size: 850px 1200px;
            margin: 0px;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.5;
            background-color: #f8fafc;
        }

        table {
            border-collapse: collapse;
        }

        .invoice-container {
            width: 100%;
            max-width: 850px;
            height: 100%;
            margin: 0 auto;
            background: #ffffff;
            padding: 0;
        }

        .invoice-inner {
            padding: 45px 50px;
        }

        /* Header with Logo and Title */
        .invoice-header {
            width: 100%;
            margin-bottom: 35px;
            text-align: center;
        }

        .invoice-header td {
            vertical-align: middle;
            border: none;
            padding: 0;
            text-align: center;
        }

        .invoice-title-wrapper {
            display: inline-block;
            border-bottom: 3px solid #000000;
            /* padding-bottom: 15px; */
            padding-left: 40px;
            padding-right: 40px;
            margin-bottom: 10px;
        }

        .company-logo {
            height: 55px;
            max-width: 180px;
        }

        .invoice-title {
            font-size: 30px;
            font-weight: 700;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1;
        }

        /* Document Info Bar */
        .document-info-bar {
            width: 100%;
            background-color: #f1f5f9;
            border-left: 4px solid #000000;
            margin-bottom: 35px;
        }

        .document-info-bar td {
            padding: 15px 20px;
            border: none;
        }

        .document-info-bar td:first-child {
            width: 50%;
            text-align: left;
        }

        .document-info-bar td:last-child {
            width: 50%;
            text-align: right;
        }

        .info-label {
            color: #000000;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 3px;
        }

        .info-value {
            color: #000000;
            font-size: 15px;
            font-weight: 700;
            display: block;
        }

        /* Party Details Section */
        .party-details {
            width: 100%;
            margin-bottom: 40px;
        }

        .party-details td {
            width: 50%;
            vertical-align: top;
            padding: 0 15px;
            border: none;
        }

        .party-details td:first-child {
            padding-left: 0;
        }

        .party-details td:last-child {
            padding-right: 0;
        }

        .party-box {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            min-height: 120px;
        }

        .party-header {
            color: #000000;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #cbd5e1;
        }

        .party-name {
            color: #000000;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .party-address {
            color: #000000;
            font-size: 12px;
            line-height: 1.7;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            margin-bottom: 30px;
            border: 2px solid #e2e8f0;
        }

        .items-table thead {
            background-color: #000000;
        }

        .items-table thead th {
            padding: 16px 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #ffffff;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
        }

        .items-table thead th.align-center {
            text-align: center;
        }

        .items-table thead th.align-right {
            text-align: right;
        }

        .items-table tbody td {
            padding: 14px;
            color: #334155;
            font-size: 12px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            white-space: nowrap;
        }

        .items-table tbody tr {
            background-color: #ffffff;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .items-table tbody td.align-center {
            text-align: center;
        }

        .items-table tbody td.align-right {
            text-align: right;
        }

        .item-name {
            color: #000000;
            font-weight: 600;
            font-size: 13px;
            white-space: normal;
            word-break: break-word;
            word-wrap: break-word;
            max-width: 100%;
            line-height: 1.4;
        }

        .item-specs {
            font-size: 11px;
            color: #000000;
            line-height: 1.6;
            margin-top: 3px;
        }

        .item-specs strong {
            color: #000000;
            font-weight: 600;
        }

        .not-applicable {
            color: #94a3b8;
            font-style: italic;
            font-size: 11px;
        }

        /* Summary Section */
        .invoice-summary {
            width: 100%;
            margin-top: 30px;
        }

        .invoice-summary td {
            vertical-align: top;
            border: none;
        }

        .invoice-summary td.notes-cell {
            width: 50%;
            padding-right: 25px;
        }

        .invoice-summary td.totals-cell {
            width: 50%;
        }

        /* Ensure totals area is right-aligned for PDF renderers (avoid inline-block on table rows) */
        .invoice-summary td.totals-cell {
            text-align: right;
        }

        .payment-notice {
            background-color: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 18px;
        }

        .notice-title {
            color: #000000;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .notice-text {
            color: #475569;
            font-size: 12px;
            line-height: 1.6;
        }

        .notice-text strong {
            color: #000000;
            font-weight: 700;
        }

        /* Totals Table */
        .totals-table {
            width: auto;
            float: right;
            border: 2px solid #e2e8f0;
        }

        .totals-table td {
            padding: 13px 18px;
            font-size: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
        }

        .totals-table td.label-cell {
            color: #000000;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-align: left;
            width: 60%;
        }

        .totals-table td.amount-cell {
            text-align: right;
            color: #000000;
            font-weight: 700;
            font-size: 13px;
            width: 40%;
        }

        .totals-table tr.subtotal-row {
            background-color: #f8fafc;
        }

        .totals-table tr.total-row {
            background-color: #000000;
        }

        .totals-table tr.total-row td {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            padding: 16px 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        /* Column Widths */
        .col-index {
            width: 6%;
        }

        .col-description {
            width: 35%;
        }

        .col-price {
            width: 14%;
        }

        .col-quantity {
            width: 10%;
        }

        .col-amount {
            width: 15%;
        }

        .col-attributes {
            width: 20%;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-inner">

            <!-- Header Section -->
            <table class="invoice-header">
                <tr>
                    <td>
                        <div class="invoice-title-wrapper">
                            <div class="invoice-title">INVOICE</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Logo Section -->
            <table style="width: 100%; margin-bottom: 35px;">
                <tr>
                    <td style="text-align: center;">
                        <img src="{{ $base64 }}" alt="Company Logo" class="company-logo">
                    </td>
                </tr>
            </table>

            <!-- Document Info Bar -->
            <table class="document-info-bar">
                <tr>
                    <td>
                        <span class="info-label">Document Number</span>
                        <span class="info-value">{{ $order->hashed_id }}</span>
                    </td>
                    <td>
                        <span class="info-label">Issue Date</span>
                        <span class="info-value">{{ formatTime($order->created_at, 'M d, Y') }}</span>
                    </td>
                </tr>
            </table>

            <!-- Party Details -->
            <table class="party-details">
                <tr>
                    <td>
                        <div class="party-box">
                            <div class="party-header">BILLED FROM</div>
                            <div class="party-name">{{ $settings->website_name }}</div>
                            <div class="party-address" style="color: black">
                                {{ $settings->address ?? 'Address not provided' }}<br>
                                <strong>Phone:</strong> {{ $settings->phone_number ?? 'N/A' }}<br>
                                <strong>Email:</strong> {{ $settings->email ?? 'N/A' }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="party-box">
                            <div class="party-header">BILLED TO</div>
                            <div class="party-name">{{ $order->customer_name }}</div>
                            <div class="party-address" style="color: black">
                                {{ $order->customer_address ?? 'Address not provided' }}<br>
                                <strong>Phone:</strong> {{ $order->customer_phone_number ?? 'N/A' }}<br>
                                <strong>Email:</strong> {{ $order->customer_email ?? ($order->user->email ?? 'N/A') }}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="col-index align-center">SL</th>
                        <th class="col-description">Description</th>
                        <th class="col-price">Unit Price</th>
                        <th class="col-quantity align-center">Quantity</th>
                        <th class="col-amount align-right">Amount</th>
                        <th class="col-attributes">Specifications</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="align-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="item-name">{{ $item->product?->name }}</div>
                            </td>
                            <td>{{ showAmount($item->product?->final_price, 1, 2, 2) }}</td>
                            <td class="align-center"><strong>{{ $item->quantity }}</strong></td>
                            <td class="align-right">
                                <strong>{{ showAmount(intval($item->quantity) * intval($item->product?->final_price), 1, 2, 2) }}</strong>
                            </td>
                            <td>
                                @if ($item->variation)
                                    <div class="item-specs">
                                        @foreach ($item->variation->attributeValues as $attrValue)
                                            <strong>{{ $attrValue->attribute->name }}:</strong>
                                            {{ $attrValue->value }}<br>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="not-applicable">No variations</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary Section -->
            <table class="invoice-summary">
                <tr>
                    <td class="notes-cell">
                        <div class="payment-notice">
                            <div class="notice-title">Payment Information</div>
                            <div class="notice-text">
                                <strong>Method:</strong>
                                {{ str_replace('_', ' ', ucwords($order->payment_method)) ?? 'Cash On Delivery' }}
                            </div>
                        </div>
                    </td>
                    <td class="totals-cell">
                        <table class="totals-table">
                            <tr class="subtotal-row">
                                <td class="label-cell">Subtotal</td>
                                <td class="amount-cell">{{ showAmount($order->subtotal, 1, 2, 2) }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td class="label-cell">Discount Applied</td>
                                <td class="amount-cell">{{ showAmount($order->discount, 1, 2, 2) }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td class="label-cell">Shipping Cost</td>
                                <td class="amount-cell">{{ showAmount($order->delivery_charge, 1, 2, 2) }}</td>
                            </tr>
                            @if ($order->tax > 0)
                                <tr class="subtotal-row">
                                    <td class="label-cell">VAT/Tax</td>
                                    <td class="amount-cell">{{ showAmount($order->tax, 1, 2, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="total-row" style="white-space: nowrap">
                                <td class="label-cell" style="white-space: nowrap">Total Amount</td>
                                <td class="amount-cell" style="white-space: nowrap">
                                    {{ showAmount($order->total, 1, 2, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
