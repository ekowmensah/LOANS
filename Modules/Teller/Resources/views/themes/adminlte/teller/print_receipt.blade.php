<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt - #{{ $transaction->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 20px;
            color: #000;
        }

        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 0;
        }

        .receipt-header {
            background: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 2px dashed #000;
        }

        .receipt-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receipt-header .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .receipt-header .transaction-type {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .receipt-body {
            padding: 20px;
        }

        .section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ccc;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #000;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .row-label {
            font-weight: normal;
            color: #333;
        }

        .row-value {
            font-weight: bold;
            text-align: right;
            color: #000;
        }

        .amount-box {
            background: #f5f5f5;
            border: 2px solid #000;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-label {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: bold;
            color: #000;
        }

        .balance-box {
            background: #000;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .balance-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .balance-value {
            font-size: 28px;
            font-weight: bold;
        }

        .receipt-footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 2px dashed #000;
        }

        .footer-note {
            font-size: 10px;
            color: #666;
            margin-top: 10px;
            line-height: 1.5;
        }

        .success-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .divider {
            border-top: 2px dashed #000;
            margin: 20px 0;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt {
                max-width: 100%;
                border: none;
            }

            @page {
                margin: 0;
                size: 80mm auto;
            }
        }

        /* Thermal printer optimization */
        @media print and (max-width: 80mm) {
            .receipt {
                max-width: 80mm;
            }

            .amount-value {
                font-size: 24px;
            }

            .balance-value {
                font-size: 20px;
            }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">
    <div class="receipt">
        <!-- Header -->
        <div class="receipt-header">
            <div class="company-name">{{ config('app.name', 'ULTLOANS') }}</div>
            <div class="success-icon">✓</div>
            <h1>RECEIPT</h1>
            <div class="transaction-type">{{ strtoupper($transaction->name) }}</div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Transaction Info -->
            <div class="section">
                <div class="section-title">Transaction Details</div>
                <div class="row">
                    <span class="row-label">Receipt No:</span>
                    <span class="row-value">#{{ $transaction->id }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Date:</span>
                    <span class="row-value">{{ \Carbon\Carbon::parse($transaction->submitted_on)->format('d M Y') }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Time:</span>
                    <span class="row-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Teller:</span>
                    <span class="row-value">{{ $transaction->created_by->first_name ?? '' }} {{ $transaction->created_by->last_name ?? '' }}</span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="row">
                    <span class="row-label">Name:</span>
                    <span class="row-value">{{ $savings->client->first_name }} {{ $savings->client->last_name }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Account:</span>
                    <span class="row-value">{{ $savings->account_number }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Product:</span>
                    <span class="row-value">{{ $savings->savings_product->name }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Branch:</span>
                    <span class="row-value">{{ $savings->branch->name }}</span>
                </div>
            </div>

            <!-- Amount -->
            <div class="amount-box">
                <div class="amount-label">
                    {{ $transaction->savings_transaction_type_id == 1 ? 'DEPOSIT' : 'WITHDRAWAL' }}
                </div>
                <div class="amount-value">
                    {{ $savings->currency->symbol }} {{ number_format($transaction->amount, 2) }}
                </div>
            </div>

            <!-- Payment Method -->
            @if($transaction->payment_detail)
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="row">
                    <span class="row-label">Method:</span>
                    <span class="row-value">{{ $transaction->payment_detail->payment_type->name ?? 'N/A' }}</span>
                </div>
                @if($transaction->payment_detail->receipt)
                <div class="row">
                    <span class="row-label">Ref:</span>
                    <span class="row-value">{{ $transaction->payment_detail->receipt }}</span>
                </div>
                @endif
            </div>
            @endif

            <div class="divider"></div>

            <!-- Balance -->
            <div class="balance-box">
                <div class="balance-label">New Balance</div>
                <div class="balance-value">
                    {{ $savings->currency->symbol }} {{ number_format($new_balance, 2) }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div style="font-weight: bold; margin-bottom: 10px;">THANK YOU!</div>
            <div class="footer-note">
                This is a computer-generated receipt.<br>
                No signature required.<br>
                For inquiries, contact your branch.<br>
                <br>
                Printed: {{ \Carbon\Carbon::now()->format('d M Y h:i A') }}
            </div>
        </div>
    </div>
</body>
</html>
