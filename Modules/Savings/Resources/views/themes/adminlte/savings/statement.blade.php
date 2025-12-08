@extends('core::layouts.master')
@section('title')
    Account Statement
@endsection
@section('styles')
<style>
    .statement-container {
        background: white;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .bank-header {
        border-bottom: 3px solid #1e40af;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .bank-logo {
        font-size: 32px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 5px;
    }
    
    .bank-tagline {
        color: #6b7280;
        font-size: 14px;
    }
    
    .statement-title {
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 30px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .account-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }
    
    .info-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .info-value {
        color: #1f2937;
        font-weight: 600;
    }
    
    .date-range-box {
        background: #dbeafe;
        border-left: 4px solid #1e40af;
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: 4px;
    }
    
    .date-range-box strong {
        color: #1e40af;
    }
    
    .statement-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
    }
    
    .statement-table thead {
        background: #1e40af;
        color: white;
    }
    
    .statement-table th {
        padding: 12px 8px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    .statement-table th.text-right,
    .statement-table td.text-right {
        text-align: right;
    }
    
    .statement-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .statement-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .statement-table td {
        padding: 12px 8px;
        color: #374151;
    }
    
    .transaction-type {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .type-deposit {
        background: #d1fae5;
        color: #065f46;
    }
    
    .type-withdrawal {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .type-interest {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .type-charge {
        background: #fef3c7;
        color: #92400e;
    }
    
    .amount-debit {
        color: #dc2626;
        font-weight: 600;
    }
    
    .amount-credit {
        color: #059669;
        font-weight: 600;
    }
    
    .balance-cell {
        font-weight: 700;
        color: #1f2937;
    }
    
    .summary-box {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin: 30px 0;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .summary-item {
        text-align: center;
        padding: 15px;
        background: white;
        border-radius: 6px;
    }
    
    .summary-label {
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .summary-value {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .summary-value.positive {
        color: #059669;
    }
    
    .summary-value.negative {
        color: #dc2626;
    }
    
    .footer-note {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #e5e7eb;
        color: #6b7280;
        font-size: 12px;
        text-align: center;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: flex-end;
    }
    
    .btn-statement {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-print {
        background: #059669;
        color: white;
    }
    
    .btn-print:hover {
        background: #047857;
        color: white;
    }
    
    .btn-pdf {
        background: #dc2626;
        color: white;
    }
    
    .btn-pdf:hover {
        background: #b91c1c;
        color: white;
    }
    
    .btn-back {
        background: #6b7280;
        color: white;
    }
    
    .btn-back:hover {
        background: #4b5563;
        color: white;
    }
    
    @media print {
        .action-buttons,
        .breadcrumb,
        .content-header {
            display: none !important;
        }
        
        .statement-container {
            box-shadow: none;
            padding: 20px;
        }
    }
    
    @media (max-width: 768px) {
        .statement-container {
            padding: 20px;
        }
        
        .account-info-grid {
            grid-template-columns: 1fr;
        }
        
        .summary-grid {
            grid-template-columns: 1fr;
        }
        
        .statement-table {
            font-size: 11px;
        }
        
        .statement-table th,
        .statement-table td {
            padding: 8px 4px;
        }
    }
</style>
@stop

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Account Statement</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings')}}">Savings Accounts</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings/'.$savings->id.'/show')}}">Account Details</a></li>
                        <li class="breadcrumb-item active">Statement</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="action-buttons">
            <a href="{{url('savings/'.$savings->id.'/show')}}" class="btn btn-statement btn-back">
                <i class="fas fa-arrow-left"></i> Back to Account
            </a>
            <a href="{{url('savings/'.$savings->id.'/statement/print')}}?start_date={{$start_date}}&end_date={{$end_date}}" target="_blank" class="btn btn-statement btn-print">
                <i class="fas fa-print"></i> Print Statement
            </a>
            <a href="{{url('savings/'.$savings->id.'/statement/pdf')}}?start_date={{$start_date}}&end_date={{$end_date}}" class="btn btn-statement btn-pdf">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>

        <div class="statement-container">
            <!-- Bank Header -->
            <div class="bank-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="bank-logo">{{ config('app.name', 'ULTLOANS') }}</div>
                        <div class="bank-tagline">Your Trusted Financial Partner</div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div style="color: #6b7280; font-size: 13px;">
                            <div><strong>Date Generated:</strong> {{ date('d M Y, h:i A') }}</div>
                            @if(!empty($savings->branch))
                            <div><strong>Branch:</strong> {{ $savings->branch->name }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statement Title -->
            <div class="statement-title">
                Account Statement
            </div>

            <!-- Account Information -->
            <div class="account-info-grid">
                <div>
                    <div class="info-item">
                        <span class="info-label">Account Holder:</span>
                        <span class="info-value">{{ $savings->client->first_name }} {{ $savings->client->middle_name }} {{ $savings->client->last_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Number:</span>
                        <span class="info-value">{{ $savings->account_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Product Type:</span>
                        <span class="info-value">{{ $savings->savings_product->name }}</span>
                    </div>
                </div>
                <div>
                    @if(!empty($savings->client->mobile))
                    <div class="info-item">
                        <span class="info-label">Mobile Number:</span>
                        <span class="info-value">{{ $savings->client->mobile }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Currency:</span>
                        <span class="info-value">{{ $savings->currency->name ?? 'GHS' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Officer:</span>
                        <span class="info-value">
                            @if(!empty($savings->savings_officer))
                                {{ $savings->savings_officer->first_name }} {{ $savings->savings_officer->last_name }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div class="date-range-box">
                <strong>Statement Period:</strong> {{ date('d M Y', strtotime($start_date)) }} to {{ date('d M Y', strtotime($end_date)) }}
            </div>

            <!-- Opening Balance -->
            <div style="padding: 15px; background: #f3f4f6; border-radius: 6px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: #374151;">Opening Balance (as of {{ date('d M Y', strtotime($start_date)) }}):</span>
                    <span style="font-size: 18px; font-weight: 700; color: #1f2937;">
                        {{ number_format($opening_balance, $savings->decimals) }}
                    </span>
                </div>
            </div>

            <!-- Transaction Table -->
            <table class="statement-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th>Reference</th>
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
                        
                        // Determine transaction type class
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
                            <td>
                                <span class="transaction-type {{ $type_class }}">
                                    {{ $transaction->savings_transaction_type->name }}
                                </span>
                            </td>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td class="text-right amount-debit">
                                @if($transaction->debit > 0)
                                    {{ number_format($transaction->debit, $savings->decimals) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right amount-credit">
                                @if($transaction->credit > 0)
                                    {{ number_format($transaction->credit, $savings->decimals) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right balance-cell">
                                {{ number_format($running_balance, $savings->decimals) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                No transactions found for the selected period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Summary Box -->
            @if($transactions->count() > 0)
            <div class="summary-box">
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">Total Deposits</div>
                        <div class="summary-value positive">
                            {{ number_format($transactions->sum('credit'), $savings->decimals) }}
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Withdrawals</div>
                        <div class="summary-value negative">
                            {{ number_format($transactions->sum('debit'), $savings->decimals) }}
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Closing Balance</div>
                        <div class="summary-value">
                            {{ number_format($running_balance, $savings->decimals) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer Note -->
            <div class="footer-note">
                <p><strong>Important Notice:</strong> This is a computer-generated statement and does not require a signature.</p>
                <p>For any queries or clarifications, please contact your account officer or visit your nearest branch.</p>
                <p style="margin-top: 10px;">© {{ date('Y') }} {{ config('app.name', 'ULTLOANS') }}. All rights reserved.</p>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i> Filter Statement</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('savings/'.$savings->id.'/statement') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Generate Statement
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
