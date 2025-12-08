@extends('core::layouts.master')
@section('title')
    {{ trans_choice('teller::general.teller', 1) }}
@endsection
@section('styles')
<style>
/* Ultra-Modern Bank Teller Interface */
:root {
    --teller-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --teller-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --teller-danger: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    --teller-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --teller-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

/* Teller Header */
.teller-header {
    background: var(--teller-primary);
    color: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.teller-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.teller-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    position: relative;
    z-index: 1;
}

.teller-info {
    display: flex;
    gap: 30px;
    margin-top: 15px;
    position: relative;
    z-index: 1;
}

.teller-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.2);
    padding: 10px 20px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.teller-info-item i {
    font-size: 20px;
}

/* Search Card */
.search-card-modern {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.search-input-group {
    position: relative;
}

.search-input-group input {
    height: 60px;
    border-radius: 15px;
    border: 2px solid #e9ecef;
    padding: 0 60px 0 20px;
    font-size: 18px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-input-group input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.search-btn-modern {
    position: absolute;
    right: 5px;
    top: 5px;
    height: 50px;
    width: 50px;
    border-radius: 12px;
    background: var(--teller-primary);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: all 0.3s ease;
}

.search-btn-modern:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.quick-action-btn {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.quick-action-btn:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.quick-action-btn i {
    font-size: 32px;
    color: #667eea;
    margin-bottom: 10px;
}

.quick-action-btn span {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

/* Account Card */
.account-card-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    margin-bottom: 30px;
}

.account-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.account-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.3);
    object-fit: cover;
}

.account-photo-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
}

.account-info h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.account-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.account-detail-item.balance-item {
    grid-column: span 2;
    background: rgba(255,255,255,0.25);
    padding: 25px;
    text-align: center;
}

.account-detail-item.balance-item .account-detail-label {
    font-size: 14px;
    margin-bottom: 10px;
}

.account-detail-item.balance-item .account-detail-value {
    font-size: 28px;
    font-weight: 800;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.account-detail-item {
    background: rgba(255,255,255,0.15);
    padding: 15px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.account-detail-label {
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.account-detail-value {
    font-size: 18px;
    font-weight: 700;
}

.balance-display {
    background: rgba(255,255,255,0.2);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    margin-top: 20px;
}

.balance-display .label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 10px;
}

.balance-display .amount {
    font-size: 28px;
    font-weight: 800;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Transaction Form */
.transaction-form-modern {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.transaction-type-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 30px;
}

.transaction-type-btn {
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.transaction-type-btn:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.transaction-type-btn.active {
    background: var(--teller-primary);
    border-color: transparent;
    color: white;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
}

.transaction-type-btn.deposit.active {
    background: var(--teller-success);
}

.transaction-type-btn.withdrawal.active {
    background: var(--teller-danger);
}

.transaction-type-btn i {
    font-size: 32px;
    margin-bottom: 10px;
    display: block;
}

.transaction-type-btn span {
    font-weight: 700;
    font-size: 16px;
}

.form-group-modern {
    margin-bottom: 25px;
}

.form-group-modern label {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
    display: block;
    font-size: 14px;
}

.form-group-modern input,
.form-group-modern select {
    height: 50px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0 15px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.form-group-modern input:focus,
.form-group-modern select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

/* Calculator Widget */
.calculator-widget {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.calculator-display {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: right;
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    min-height: 70px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.calculator-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.calc-btn {
    padding: 15px;
    border: none;
    border-radius: 10px;
    background: #f8f9fa;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.calc-btn:hover {
    background: #e9ecef;
    transform: scale(1.05);
}

.calc-btn.operator {
    background: var(--teller-primary);
    color: white;
}

.calc-btn.equals {
    background: var(--teller-success);
    color: white;
    grid-column: span 2;
}

.calc-btn.clear {
    background: var(--teller-danger);
    color: white;
}

/* Submit Button */
.btn-submit-modern {
    width: 100%;
    height: 60px;
    border-radius: 15px;
    background: var(--teller-primary);
    border: none;
    color: white;
    font-size: 18px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
}

.btn-submit-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
}

.btn-submit-modern.deposit {
    background: var(--teller-success);
}

.btn-submit-modern.withdrawal {
    background: var(--teller-danger);
}

/* Recent Transactions */
.recent-transactions {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.transaction-item:hover {
    background: #f8f9fa;
}

.transaction-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 15px;
}

.transaction-icon.deposit {
    background: rgba(17, 153, 142, 0.1);
    color: #11998e;
}

.transaction-icon.withdrawal {
    background: rgba(235, 51, 73, 0.1);
    color: #eb3349;
}

.transaction-details {
    flex: 1;
}

.transaction-amount {
    font-size: 18px;
    font-weight: 700;
}

.transaction-amount.deposit {
    color: #11998e;
}

.transaction-amount.withdrawal {
    color: #eb3349;
}

/* Responsive */
@media (max-width: 768px) {
    .teller-info {
        flex-direction: column;
        gap: 10px;
    }
    
    .account-details-grid {
        grid-template-columns: 1fr;
    }
    
    .transaction-type-selector {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="teller-header">
                <h1><i class="fas fa-cash-register"></i> Bank Teller Station</h1>
                <div class="teller-info">
                    <div class="teller-info-item">
                        <i class="fas fa-user"></i>
                        <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    </div>
                    <div class="teller-info-item">
                        <i class="fas fa-building"></i>
                        <span>{{ Auth::user()->branch->name ?? 'Main Branch' }}</span>
                    </div>
                    <div class="teller-info-item">
                        <i class="fas fa-clock"></i>
                        <span id="current-time">{{ date('h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Search Card -->
                <div class="search-card-modern">
                    <h4 style="margin-bottom: 20px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-search"></i> Search Account
                    </h4>
                    <div class="search-input-group">
                        <input type="text" 
                               class="form-control" 
                               v-model="account_number" 
                               placeholder="Enter account number..."
                               @keyup.enter="searchAccount">
                        <button class="search-btn-modern" @click="searchAccount" :disabled="searching">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions" v-if="!account">
                    <div class="quick-action-btn" @click="focusSearch">
                        <i class="fas fa-search"></i>
                        <span>Search Account</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('savings/create')}}'">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Account</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('client')}}'">
                        <i class="fas fa-users"></i>
                        <span>Clients</span>
                    </div>
                    <div class="quick-action-btn" onclick="window.location.href='{{url('savings')}}'">
                        <i class="fas fa-university"></i>
                        <span>All Accounts</span>
                    </div>
                </div>

                <!-- Account Card -->
                <div v-if="account" class="account-card-modern">
                    <div class="account-header">
                        <img v-if="account.client_photo" 
                             :src="'/storage/uploads/clients/' + account.client_photo" 
                             class="account-photo" 
                             alt="Client Photo">
                        <div v-else class="account-photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="account-info flex-grow-1">
                            <h3>@{{ account.client_name }}</h3>
                            <div style="opacity: 0.9; margin-top: 8px; font-size: 15px; line-height: 1.8;">
                                <div><i class="fas fa-phone"></i> @{{ account.client_mobile }}</div>
                                <div><i class="fas fa-hashtag"></i> @{{ account.account_number }} • @{{ account.product_name }} • @{{ account.branch_name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="balance-display">
                        <div class="label">AVAILABLE BALANCE</div>
                        <div class="amount">@{{ account.currency_symbol }} @{{ account.balance }}</div>
                    </div>
                </div>

                <!-- Transaction Form -->
                <div v-if="account" class="transaction-form-modern">
                    <h4 style="margin-bottom: 25px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-exchange-alt"></i> Process Transaction
                    </h4>

                    <!-- Transaction Type Selector -->
                    <div class="transaction-type-selector">
                            <div class="transaction-type-btn deposit" 
                                 :class="{active: transaction_type === 'deposit'}"
                                 @click="transaction_type = 'deposit'">
                                <i class="fas fa-arrow-down"></i>
                                <span>DEPOSIT</span>
                            </div>
                            <div class="transaction-type-btn withdrawal" 
                                 :class="{active: transaction_type === 'withdrawal'}"
                                 @click="transaction_type = 'withdrawal'">
                                <i class="fas fa-arrow-up"></i>
                                <span>WITHDRAWAL</span>
                            </div>
                    </div>

                    <form v-if="transaction_type" method="post" action="{{url('teller/transaction')}}">
                        @csrf
                        <input type="hidden" name="savings_id" v-model="account.savings_id">
                        <input type="hidden" name="transaction_type" v-model="transaction_type">

                        <div class="row" style="margin-top: 30px;">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="any" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           name="amount" 
                                           v-model="amount" 
                                           required
                                           placeholder="0.00">
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small v-if="transaction_type === 'withdrawal' && amount > account.raw_balance" 
                                           class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Amount exceeds available balance
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('date') is-invalid @enderror" 
                                           name="date" 
                                           value="{{date('Y-m-d')}}" 
                                           required>
                                    @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Payment Type <span class="text-danger">*</span></label>
                                    <v-select label="name" 
                                              :options="payment_types"
                                              :reduce="payment_type => payment_type.id"
                                              v-model="payment_type_id"
                                              placeholder="Select payment type">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off"
                                                   class="vs__search @error('payment_type_id') is-invalid @enderror"
                                                   v-bind="attributes"
                                                   v-bind:required="!payment_type_id"
                                                   v-on="events"/>
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="payment_type_id" v-model="payment_type_id">
                                    @error('payment_type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Receipt #</label>
                                    <input type="text" class="form-control" name="receipt" placeholder="Optional">
                                </div>
                            </div>
                        </div>


                        <button type="submit" 
                                class="btn-submit-modern" 
                                :class="transaction_type"
                                :disabled="!transaction_type || !amount || !payment_type_id">
                            <i class="fas fa-check-circle"></i> 
                            Process @{{ transaction_type ? transaction_type.toUpperCase() : 'TRANSACTION' }}
                        </button>

                        <button type="button" 
                                class="btn btn-secondary btn-lg btn-block mt-3" 
                                @click="clearForm"
                                style="border-radius: 15px; height: 50px;">
                            <i class="fas fa-times"></i> Clear & New Transaction
                        </button>
                    </form>
                    
                    <div v-else style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <i class="fas fa-hand-pointer" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p style="font-size: 16px; font-weight: 600;">Select transaction type above to continue</p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Calculator Button -->
                <div class="calculator-widget" style="padding: 20px; text-align: center; cursor: pointer;" data-toggle="modal" data-target="#calculatorModal">
                    <i class="fas fa-calculator" style="font-size: 48px; color: #667eea; margin-bottom: 15px;"></i>
                    <h5 style="font-weight: 700; color: #2c3e50; margin: 0;">Quick Calculator</h5>
                    <p style="color: #7f8c8d; font-size: 13px; margin-top: 5px;">Click to open</p>
                </div>

                <!-- Today's Summary -->
                <div class="recent-transactions">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h5 style="margin: 0; font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-chart-line"></i> Today's Summary
                        </h5>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#todayTransactionsModal" style="border-radius: 8px;">
                            <i class="fas fa-list"></i> View All
                        </button>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-icon deposit">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Deposits</div>
                            <div class="transaction-amount deposit">GH₵ {{ number_format($today_deposits ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-icon withdrawal">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Withdrawals</div>
                            <div class="transaction-amount withdrawal">GH₵ {{ number_format($today_withdrawals ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Total Transactions</div>
                            <div style="font-size: 18px; font-weight: 700; color: #667eea;">{{ $today_count ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="transaction-item" style="border-bottom: none; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; margin-top: 10px;">
                        <div class="transaction-icon" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600; color: #7f8c8d; font-size: 13px;">Expected Cash on Hand</div>
                            <div style="font-size: 18px; font-weight: 700; color: {{ ($expected_cash ?? 0) >= 0 ? '#11998e' : '#eb3349' }};">
                                GH₵ {{ number_format($expected_cash ?? 0, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="recent-transactions mt-3">
                    <h5 style="margin-bottom: 20px; font-weight: 700; color: #2c3e50;">
                        <i class="fas fa-link"></i> Quick Links
                    </h5>
                    <a href="{{url('savings')}}" class="transaction-item" style="text-decoration: none; color: inherit;">
                        <div class="transaction-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">All Savings Accounts</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                    <a href="{{url('client')}}" class="transaction-item" style="text-decoration: none; color: inherit;">
                        <div class="transaction-icon" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">Client Management</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                    <a href="{{url('report/savings/transaction')}}" class="transaction-item" style="text-decoration: none; color: inherit; border-bottom: none;">
                        <div class="transaction-icon" style="background: rgba(240, 147, 251, 0.1); color: #f093fb;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="transaction-details">
                            <div style="font-weight: 600;">Transaction Reports</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Today's Transactions Modal -->
        <div class="modal fade" id="todayTransactionsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title" style="font-weight: 700;">
                            <i class="fas fa-list"></i> Today's Transactions
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 30px;">
                        <div class="table-responsive">
                            <table class="table table-hover" id="todayTransactionsTable">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Account</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $today_transactions = \Modules\Savings\Entities\SavingsTransaction::with(['savings', 'savings.client'])
                                            ->where('created_by_id', Auth::id())
                                            ->whereDate('created_at', date('Y-m-d'))
                                            ->where('reversed', 0)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                                    @endphp
                                    @forelse($today_transactions as $txn)
                                    <tr>
                                        <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($txn->created_at)->timezone(config('app.timezone', 'UTC'))->format('h:i A') }}</td>
                                        <td>
                                            @if($txn->savings_transaction_type_id == 1)
                                                <span class="badge badge-success" style="padding: 6px 12px; border-radius: 8px;">
                                                    <i class="fas fa-arrow-down"></i> Deposit
                                                </span>
                                            @else
                                                <span class="badge badge-danger" style="padding: 6px 12px; border-radius: 8px;">
                                                    <i class="fas fa-arrow-up"></i> Withdrawal
                                                </span>
                                            @endif
                                        </td>
                                        <td style="font-family: monospace; font-weight: 600;">{{ $txn->savings->account_number ?? 'N/A' }}</td>
                                        <td>{{ $txn->savings->client->first_name ?? '' }} {{ $txn->savings->client->last_name ?? '' }}</td>
                                        <td style="font-weight: 700; color: {{ $txn->savings_transaction_type_id == 1 ? '#11998e' : '#eb3349' }};">
                                            GH₵ {{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td>
                                            <a href="{{url('teller/receipt/' . $txn->id . '/print')}}" target="_blank" class="btn btn-sm btn-info" style="border-radius: 8px;">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="padding: 40px; color: #95a5a6;">
                                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                                            <p style="font-weight: 600; margin: 0;">No transactions yet today</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 10px;">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calculator Modal -->
        <div class="modal fade" id="calculatorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title" style="font-weight: 700;">
                            <i class="fas fa-calculator"></i> Quick Calculator
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 30px;">
                        <div class="calculator-display" style="margin-bottom: 20px;">@{{ calculatorDisplay || '0' }}</div>
                        <div class="calculator-buttons">
                            <button class="calc-btn" @click="calcInput('7')">7</button>
                            <button class="calc-btn" @click="calcInput('8')">8</button>
                            <button class="calc-btn" @click="calcInput('9')">9</button>
                            <button class="calc-btn operator" @click="calcInput('/')">÷</button>
                            
                            <button class="calc-btn" @click="calcInput('4')">4</button>
                            <button class="calc-btn" @click="calcInput('5')">5</button>
                            <button class="calc-btn" @click="calcInput('6')">6</button>
                            <button class="calc-btn operator" @click="calcInput('*')">×</button>
                            
                            <button class="calc-btn" @click="calcInput('1')">1</button>
                            <button class="calc-btn" @click="calcInput('2')">2</button>
                            <button class="calc-btn" @click="calcInput('3')">3</button>
                            <button class="calc-btn operator" @click="calcInput('-')">−</button>
                            
                            <button class="calc-btn" @click="calcInput('0')">0</button>
                            <button class="calc-btn" @click="calcInput('.')">.</button>
                            <button class="calc-btn clear" @click="calcClear">C</button>
                            <button class="calc-btn operator" @click="calcInput('+')">+</button>
                            
                            <button class="calc-btn equals" @click="calcEquals">=</button>
                            <button class="calc-btn" @click="useCalcResult" data-dismiss="modal">Use</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                account_number: '',
                account: null,
                searching: false,
                transaction_type: '',
                amount: '',
                payment_type_id: '',
                payment_types: {!! json_encode($payment_types) !!},
                calculatorDisplay: '',
                calculatorValue: '',
                calculatorOperator: '',
                calculatorWaitingForOperand: false
            },
            methods: {
                searchAccount() {
                    if (!this.account_number) {
                        toastr.warning('Please enter an account number');
                        return;
                    }

                    this.searching = true;
                    this.account = null;

                    axios.post('{{url("teller/search")}}', {
                        account_number: this.account_number
                    })
                    .then(response => {
                        this.account = response.data.data;
                        toastr.success('Account found');
                    })
                    .catch(error => {
                        if (error.response) {
                            toastr.error(error.response.data.message || 'Account not found');
                        } else {
                            toastr.error('An error occurred');
                        }
                    })
                    .finally(() => {
                        this.searching = false;
                    });
                },
                clearForm() {
                    this.account_number = '';
                    this.account = null;
                    this.transaction_type = '';
                    this.amount = '';
                    this.payment_type_id = '';
                },
                focusSearch() {
                    document.querySelector('.search-input-group input').focus();
                },
                // Calculator methods
                calcInput(value) {
                    if (this.calculatorWaitingForOperand) {
                        this.calculatorDisplay = value;
                        this.calculatorWaitingForOperand = false;
                    } else {
                        this.calculatorDisplay = this.calculatorDisplay === '' ? value : this.calculatorDisplay + value;
                    }
                },
                calcClear() {
                    this.calculatorDisplay = '';
                    this.calculatorValue = '';
                    this.calculatorOperator = '';
                    this.calculatorWaitingForOperand = false;
                },
                calcEquals() {
                    try {
                        const result = eval(this.calculatorDisplay);
                        this.calculatorDisplay = result.toString();
                        this.calculatorValue = result;
                    } catch (e) {
                        this.calculatorDisplay = 'Error';
                        setTimeout(() => this.calcClear(), 1000);
                    }
                },
                useCalcResult() {
                    if (this.calculatorDisplay && !isNaN(this.calculatorDisplay)) {
                        this.amount = parseFloat(this.calculatorDisplay).toFixed(2);
                        toastr.success('Amount set from calculator');
                    }
                }
            }
        });

        // Update clock every second with seconds display
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit',
                hour12: true 
            });
            document.getElementById('current-time').textContent = timeString;
        }
        
        // Update immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>
@endsection
