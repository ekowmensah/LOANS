@extends('core::layouts.master')
@section('title')
    Transaction Receipt
@endsection
@section('styles')
<style>
/* Receipt Styles */
.receipt-container {
    max-width: 800px;
    margin: 30px auto;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.receipt-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    text-align: center;
    position: relative;
}

.receipt-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.receipt-header h1 {
    font-size: 32px;
    font-weight: 800;
    margin: 0 0 10px 0;
    position: relative;
    z-index: 1;
}

.receipt-header .transaction-type {
    font-size: 18px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    z-index: 1;
}

.receipt-success-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 40px;
    position: relative;
    z-index: 1;
}

.receipt-body {
    padding: 40px;
}

.receipt-section {
    margin-bottom: 30px;
}

.receipt-section h3 {
    font-size: 14px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
    margin-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.receipt-row:last-child {
    border-bottom: none;
}

.receipt-label {
    color: #7f8c8d;
    font-weight: 600;
    font-size: 14px;
}

.receipt-value {
    color: #2c3e50;
    font-weight: 700;
    font-size: 14px;
    text-align: right;
}

.receipt-amount-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    margin: 30px 0;
    border: 2px solid #e9ecef;
}

.receipt-amount-box.deposit {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-color: #11998e;
}

.receipt-amount-box.withdrawal {
    background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
    border-color: #eb3349;
}

.receipt-amount-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    margin-bottom: 10px;
}

.receipt-amount-value {
    font-size: 42px;
    font-weight: 800;
    color: #2c3e50;
}

.receipt-balance-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    margin-top: 20px;
}

.receipt-balance-label {
    font-size: 14px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.receipt-balance-value {
    font-size: 36px;
    font-weight: 800;
}

.receipt-footer {
    background: #f8f9fa;
    padding: 30px 40px;
    text-align: center;
    border-top: 2px solid #e9ecef;
}

.receipt-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

.btn-receipt {
    padding: 15px 30px;
    border-radius: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
}

.btn-receipt-print {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-receipt-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-receipt-new {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.btn-receipt-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(17, 153, 142, 0.4);
    color: white;
}

.receipt-note {
    font-size: 12px;
    color: #95a5a6;
    margin-top: 20px;
    font-style: italic;
}

@media print {
    .receipt-actions,
    .receipt-note,
    .content-header,
    .main-sidebar,
    .main-header {
        display: none !important;
    }
    
    .receipt-container {
        box-shadow: none;
        max-width: 100%;
    }
    
    body {
        background: white;
    }
}
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Transaction Receipt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('teller')}}">Teller</a></li>
                    <li class="breadcrumb-item active">Receipt</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="receipt-header">
            <div class="receipt-success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>TRANSACTION SUCCESSFUL</h1>
            <div class="transaction-type">{{ strtoupper($transaction->name) }}</div>
        </div>

        <!-- Receipt Body -->
        <div class="receipt-body">
            <!-- Transaction Details -->
            <div class="receipt-section">
                <h3><i class="fas fa-receipt"></i> Transaction Details</h3>
                <div class="receipt-row">
                    <span class="receipt-label">Transaction ID</span>
                    <span class="receipt-value">#{{ $transaction->id }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Date & Time</span>
                    <span class="receipt-value">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, h:i A') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Transaction Date</span>
                    <span class="receipt-value">{{ \Carbon\Carbon::parse($transaction->submitted_on)->format('d M Y') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Processed By</span>
                    <span class="receipt-value">{{ $transaction->created_by->first_name ?? '' }} {{ $transaction->created_by->last_name ?? '' }}</span>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="receipt-section">
                <h3><i class="fas fa-user"></i> Customer Details</h3>
                <div class="receipt-row">
                    <span class="receipt-label">Name</span>
                    <span class="receipt-value">{{ $savings->client->first_name }} {{ $savings->client->last_name }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Account Number</span>
                    <span class="receipt-value">{{ $savings->account_number }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Product</span>
                    <span class="receipt-value">{{ $savings->savings_product->name }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Branch</span>
                    <span class="receipt-value">{{ $savings->branch->name }}</span>
                </div>
            </div>

            <!-- Amount -->
            <div class="receipt-amount-box {{ $transaction->savings_transaction_type_id == 1 ? 'deposit' : 'withdrawal' }}">
                <div class="receipt-amount-label">
                    {{ $transaction->savings_transaction_type_id == 1 ? 'DEPOSIT AMOUNT' : 'WITHDRAWAL AMOUNT' }}
                </div>
                <div class="receipt-amount-value">
                    {{ $savings->currency->symbol }} {{ number_format($transaction->amount, 2) }}
                </div>
            </div>

            <!-- Payment Details -->
            @if($transaction->payment_detail)
            <div class="receipt-section">
                <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                <div class="receipt-row">
                    <span class="receipt-label">Payment Method</span>
                    <span class="receipt-value">{{ $transaction->payment_detail->payment_type->name ?? 'N/A' }}</span>
                </div>
                @if($transaction->payment_detail->receipt)
                <div class="receipt-row">
                    <span class="receipt-label">Receipt Number</span>
                    <span class="receipt-value">{{ $transaction->payment_detail->receipt }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- New Balance -->
            <div class="receipt-balance-box">
                <div class="receipt-balance-label">New Account Balance</div>
                <div class="receipt-balance-value">
                    {{ $savings->currency->symbol }} {{ number_format($new_balance, 2) }}
                </div>
            </div>
        </div>

        <!-- Receipt Footer -->
        <div class="receipt-footer">
            <div class="receipt-actions">
                <a href="{{url('teller/receipt/' . $transaction->id . '/print')}}" target="_blank" class="btn btn-receipt btn-receipt-print">
                    <i class="fas fa-print"></i> Print Receipt
                </a>
                <a href="{{url('teller')}}" class="btn btn-receipt btn-receipt-new">
                    <i class="fas fa-plus"></i> New Transaction
                </a>
            </div>
            <div class="receipt-note">
                This is a computer-generated receipt and does not require a signature.
                <br>For any queries, please contact your branch.
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Auto-print option (optional)
    // window.onload = function() {
    //     setTimeout(function() {
    //         window.print();
    //     }, 500);
    // };
</script>
@endsection
