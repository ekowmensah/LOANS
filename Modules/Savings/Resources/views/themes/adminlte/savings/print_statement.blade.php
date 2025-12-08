<!DOCTYPE html>
<html>
<head>
    <title>Account Statement - {{ $savings->account_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #333; padding: 20px; }
        .statement-container { max-width: 1000px; margin: 0 auto; }
        .bank-header { border-bottom: 3px solid #1e40af; padding-bottom: 15px; margin-bottom: 20px; }
        .bank-logo { font-size: 24px; font-weight: 700; color: #1e40af; }
        .bank-tagline { color: #666; font-size: 11px; }
        .statement-title { text-align: center; font-size: 18px; font-weight: 700; margin: 20px 0; text-transform: uppercase; }
        .account-info { margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 5px 0; }
        .info-label { color: #666; font-weight: 500; }
        .info-value { font-weight: 600; }
        .date-range { background: #f0f0f0; padding: 10px; margin: 15px 0; border-left: 4px solid #1e40af; }
        .statement-table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 11px; }
        .statement-table thead { background: #1e40af; color: white; }
        .statement-table th { padding: 8px 5px; text-align: left; font-weight: 600; font-size: 10px; }
        .statement-table th.text-right, .statement-table td.text-right { text-align: right; }
        .statement-table tbody tr { border-bottom: 1px solid #ddd; }
        .statement-table td { padding: 8px 5px; }
        .transaction-type { padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: 600; }
        .type-deposit { background: #d1fae5; color: #065f46; }
        .type-withdrawal { background: #fee2e2; color: #991b1b; }
        .type-interest { background: #dbeafe; color: #1e40af; }
        .type-charge { background: #fef3c7; color: #92400e; }
        .amount-debit { color: #dc2626; font-weight: 600; }
        .amount-credit { color: #059669; font-weight: 600; }
        .balance-cell { font-weight: 700; }
        .summary-box { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin: 20px 0; }
        .summary-grid { display: flex; justify-content: space-around; }
        .summary-item { text-align: center; }
        .summary-label { color: #666; font-size: 10px; margin-bottom: 5px; }
        .summary-value { font-size: 16px; font-weight: 700; }
        .footer-note { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; color: #666; font-size: 10px; text-align: center; }
        @media print {
            body { padding: 10px; }
            .statement-table { font-size: 10px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="statement-container">
        <div class="bank-header">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <div class="bank-logo">{{ config('app.name', 'ULTLOANS') }}</div>
                        <div class="bank-tagline">Your Trusted Financial Partner</div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <div style="color: #666; font-size: 11px;">
                            <div><strong>Date:</strong> {{ date('d M Y, h:i A') }}</div>
                            @if(!empty($savings->branch))
                            <div><strong>Branch:</strong> {{ $savings->branch->name }}</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="statement-title">Account Statement</div>

        <div class="account-info">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <div class="info-row">
                            <span class="info-label">Account Holder:</span>
                            <span class="info-value">{{ $savings->client->first_name }} {{ $savings->client->middle_name }} {{ $savings->client->last_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Account Number:</span>
                            <span class="info-value">{{ $savings->account_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Product Type:</span>
                            <span class="info-value">{{ $savings->savings_product->name }}</span>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        @if(!empty($savings->client->mobile))
                        <div class="info-row">
                            <span class="info-label">Mobile:</span>
                            <span class="info-value">{{ $savings->client->mobile }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Currency:</span>
                            <span class="info-value">{{ $savings->currency->name ?? 'GHS' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Officer:</span>
                            <span class="info-value">
                                @if(!empty($savings->savings_officer))
                                    {{ $savings->savings_officer->first_name }} {{ $savings->savings_officer->last_name }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="date-range">
            <strong>Statement Period:</strong> {{ date('d M Y', strtotime($start_date)) }} to {{ date('d M Y', strtotime($end_date)) }}
        </div>

        <div style="padding: 10px; background: #f3f4f6; margin-bottom: 15px;">
            <table style="width: 100%;">
                <tr>
                    <td><strong>Opening Balance ({{ date('d M Y', strtotime($start_date)) }}):</strong></td>
                    <td style="text-align: right; font-size: 14px; font-weight: 700;">{{ number_format($opening_balance, $savings->decimals) }}</td>
                </tr>
            </table>
        </div>

        <table class="statement-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Ref</th>
                    <th>Description</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php $running_balance = $opening_balance; ?>
                @forelse($transactions as $transaction)
                    <?php
                    $running_balance = $running_balance + $transaction->credit - $transaction->debit;
                    $type_class = 'type-charge';
                    if ($transaction->savings_transaction_type_id == 1) {
                        $type_class = 'type-deposit';
                    } elseif ($transaction->savings_transaction_type_id == 2) {
                        $type_class = 'type-withdrawal';
                    } elseif ($transaction->savings_transaction_type_id == 11) {
                        $type_class = 'type-interest';
                    }
                    ?>
                    <tr>
                        <td>{{ date('d M Y', strtotime($transaction->submitted_on)) }}</td>
                        <td><span class="transaction-type {{ $type_class }}">{{ $transaction->savings_transaction_type->name }}</span></td>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->description ?? '-' }}</td>
                        <td class="text-right amount-debit">{{ $transaction->debit > 0 ? number_format($transaction->debit, $savings->decimals) : '-' }}</td>
                        <td class="text-right amount-credit">{{ $transaction->credit > 0 ? number_format($transaction->credit, $savings->decimals) : '-' }}</td>
                        <td class="text-right balance-cell">{{ number_format($running_balance, $savings->decimals) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($transactions->count() > 0)
        <div class="summary-box">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Deposits</div>
                    <div class="summary-value" style="color: #059669;">{{ number_format($transactions->sum('credit'), $savings->decimals) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Withdrawals</div>
                    <div class="summary-value" style="color: #dc2626;">{{ number_format($transactions->sum('debit'), $savings->decimals) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Closing Balance</div>
                    <div class="summary-value">{{ number_format($running_balance, $savings->decimals) }}</div>
                </div>
            </div>
        </div>
        @endif

        <div class="footer-note">
            <p><strong>Important:</strong> This is a computer-generated statement and does not require a signature.</p>
            <p>For queries, contact your account officer or visit your nearest branch.</p>
            <p style="margin-top: 10px;">© {{ date('Y') }} {{ config('app.name', 'ULTLOANS') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
