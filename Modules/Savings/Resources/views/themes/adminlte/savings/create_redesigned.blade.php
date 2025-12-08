@extends('core::layouts.master')
@section('title')
    Open New Savings Account
@endsection
@section('styles')
<style>
    .account-wizard {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 20px 0;
        border-bottom: 2px solid #e9ecef;
    }
    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
        padding: 10px;
    }
    .wizard-step::after {
        content: '';
        position: absolute;
        top: 25px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #e9ecef;
        z-index: -1;
    }
    .wizard-step:last-child::after {
        display: none;
    }
    .step-number {
        width: 50px;
        height: 50px;
        line-height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: inline-block;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 8px;
        transition: all 0.3s;
    }
    .wizard-step.active .step-number {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }
    .wizard-step.completed .step-number {
        background: #28a745;
        color: #fff;
    }
    .wizard-step.completed .step-number::before {
        content: '✓';
    }
    .step-title {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
    }
    .wizard-step.active .step-title {
        color: #007bff;
    }
    .info-card {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .info-card.warning {
        border-left-color: #ffc107;
        background: #fff3cd;
    }
    .info-card.success {
        border-left-color: #28a745;
        background: #d4edda;
    }
    .account-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    .account-summary h4 {
        color: #fff;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-label {
        opacity: 0.9;
    }
    .summary-value {
        font-weight: 600;
    }
    .form-section {
        margin-bottom: 30px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    .section-title i {
        margin-right: 10px;
        color: #007bff;
    }
    .quick-info {
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        padding: 12px 15px;
        border-radius: 4px;
        font-size: 13px;
        margin-top: 5px;
    }
    .quick-info i {
        color: #007bff;
        margin-right: 5px;
    }
    .client-info-box {
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .client-info-box.selected {
        border-color: #28a745;
        background: #f8fff9;
    }
    .client-name {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .client-detail {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }
    .client-detail-label {
        color: #6c757d;
        font-weight: 500;
    }
    .client-detail-value {
        color: #2c3e50;
        font-weight: 600;
    }
    .btn-action {
        padding: 12px 30px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .charge-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 20px;
        font-size: 13px;
        margin: 5px 5px 5px 0;
    }
    .charge-badge i {
        color: #dc3545;
        cursor: pointer;
        margin-left: 8px;
    }
</style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-piggy-bank text-primary"></i> Open New Savings Account
                        <a href="{{url('savings')}}" class="btn btn-outline-secondary btn-sm ml-2">
                            <i class="fas fa-arrow-left"></i> Back to Accounts
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings')}}">Savings Accounts</a></li>
                        <li class="breadcrumb-item active">Open Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <form method="post" action="{{ url('savings/store') }}">
            {{csrf_field()}}
            
            <div class="account-wizard">
                <!-- Wizard Steps -->
                <div class="wizard-steps">
                    <div class="wizard-step" :class="{active: step === 1, completed: step > 1}">
                        <div class="step-number">1</div>
                        <div class="step-title">Client & Product</div>
                    </div>
                    <div class="wizard-step" :class="{active: step === 2, completed: step > 2}">
                        <div class="step-number">2</div>
                        <div class="step-title">Account Details</div>
                    </div>
                    <div class="wizard-step" :class="{active: step === 3, completed: step > 3}">
                        <div class="step-number">3</div>
                        <div class="step-title">Terms & Charges</div>
                    </div>
                    <div class="wizard-step" :class="{active: step === 4}">
                        <div class="step-number">4</div>
                        <div class="step-title">Review & Submit</div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Step 1: Client & Product Selection -->
                    <div v-show="step === 1">
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-user-circle"></i> Select Client
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Search Client</label>
                                        <v-select label="name_id" :options="clients" @search="onSearch"
                                                  :reduce="client => client.id"
                                                  v-on:input="change_client"
                                                  v-model="client_id"
                                                  placeholder="Type client name or ID to search...">
                                            <template slot="no-options">
                                                Type to search for clients...
                                            </template>
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search @error('client_id') is-invalid @enderror"
                                                       v-bind="attributes"
                                                       v-bind:required="!client_id"
                                                       v-on="events" />
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-circle fa-2x text-primary mr-3"></i>
                                                    <div>
                                                        <div class="font-weight-bold">@{{ option.name }}</div>
                                                        <small class="text-muted">ID: @{{ option.id }} | Mobile: @{{ option.mobile }}</small>
                                                    </div>
                                                </div>
                                            </template>
                                            <template slot="selected-option" slot-scope="option">
                                                <div class="selected d-center">
                                                    @{{ option.name_id }}
                                                </div>
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="client_id" v-model="client_id">
                                        @error('client_id')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!-- Client Info Display -->
                                    <div v-if="selected_client" class="client-info-box selected">
                                        <div class="client-name">
                                            <i class="fas fa-check-circle text-success"></i> @{{ selected_client.name }}
                                        </div>
                                        <div class="client-detail">
                                            <span class="client-detail-label">Client ID:</span>
                                            <span class="client-detail-value">@{{ selected_client.id }}</span>
                                        </div>
                                        <div class="client-detail">
                                            <span class="client-detail-label">Mobile:</span>
                                            <span class="client-detail-value">@{{ selected_client.mobile }}</span>
                                        </div>
                                        <div class="client-detail">
                                            <span class="client-detail-label">Branch:</span>
                                            <span class="client-detail-value">@{{ selected_client.branch }}</span>
                                        </div>
                                        <div class="client-detail" v-if="selected_client.existing_savings_count > 0">
                                            <span class="client-detail-label">Existing Accounts:</span>
                                            <span class="client-detail-value text-info">@{{ selected_client.existing_savings_count }} account(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-box-open"></i> Select Product
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Savings Product</label>
                                        <v-select label="name" :options="savings_products"
                                                  :reduce="savings_product => savings_product.id"
                                                  v-on:input="change_product"
                                                  v-model="savings_product_id"
                                                  placeholder="Select a savings product...">
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search @error('savings_product_id') is-invalid @enderror"
                                                       v-bind="attributes"
                                                       v-bind:required="!savings_product_id"
                                                       v-on="events" />
                                            </template>
                                            <template slot="option" slot-scope="option">
                                                <div>
                                                    <div class="font-weight-bold">@{{ option.name }}</div>
                                                    <small class="text-muted">Interest: @{{ option.default_interest_rate }}% | Min Balance: @{{ option.minimum_balance }}</small>
                                                </div>
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="savings_product_id" v-model="savings_product_id">
                                        @error('savings_product_id')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!-- Product Info Display -->
                                    <div v-if="savings_product" class="info-card">
                                        <h6 class="font-weight-bold mb-2">
                                            <i class="fas fa-info-circle"></i> Product Features
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><strong>Interest Rate:</strong> @{{ savings_product.default_interest_rate }}% p.a.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Currency:</strong> @{{ savings_product.currency_name }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Min Balance:</strong> @{{ savings_product.minimum_balance }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Lock-in Period:</strong> @{{ savings_product.lockin_period }} @{{ savings_product.lockin_type }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-primary btn-action" @click="nextStep" :disabled="!client_id || !savings_product_id">
                                Next: Account Details <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Account Details -->
                    <div v-show="step === 2">
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-id-card"></i> Account Identification
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Account Number</label>
                                        <input type="text" name="account_number" v-model="account_number"
                                               class="form-control @error('account_number') is-invalid @enderror"
                                               placeholder="Auto-generated if left blank">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-lightbulb"></i> Leave blank for auto-generation
                                        </small>
                                        @error('account_number')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">External ID (Optional)</label>
                                        <input type="text" name="external_id" v-model="external_id"
                                               class="form-control @error('external_id') is-invalid @enderror"
                                               placeholder="External system reference">
                                        @error('external_id')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-user-tie"></i> Account Officer
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Assigned Officer</label>
                                        <v-select label="full_name" :options="users"
                                                  :reduce="user => user.id"
                                                  v-model="savings_officer_id"
                                                  placeholder="Select account officer...">
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search @error('savings_officer_id') is-invalid @enderror"
                                                       v-bind="attributes"
                                                       v-bind:required="!savings_officer_id"
                                                       v-on="events" />
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="savings_officer_id" v-model="savings_officer_id">
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Auto-assigned from client's loan officer
                                        </small>
                                        @error('savings_officer_id')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-money-bill-wave"></i> Opening Balance
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Initial Deposit Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" name="automatic_opening_balance"
                                                   class="form-control @error('automatic_opening_balance') is-invalid @enderror numeric"
                                                   v-model="automatic_opening_balance" required
                                                   placeholder="0.00">
                                        </div>
                                        <div class="quick-info" v-if="savings_product && savings_product.minimum_balance > 0">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Minimum balance required: <strong>@{{ savings_product.minimum_balance }}</strong>
                                        </div>
                                        @error('automatic_opening_balance')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Submission Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <flat-pickr v-model="submitted_on_date"
                                                    class="form-control @error('submitted_on_date') is-invalid @enderror"
                                                    name="submitted_on_date" required>
                                            </flat-pickr>
                                        </div>
                                        @error('submitted_on_date')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary btn-action" @click="prevStep">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary btn-action ml-2" @click="nextStep">
                                Next: Terms & Charges <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Terms & Charges -->
                    <div v-show="step === 3">
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-percentage"></i> Interest & Terms
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Interest Rate (% p.a.)</label>
                                        <div class="input-group">
                                            <input type="text" name="interest_rate" v-model="interest_rate"
                                                   class="form-control @error('interest_rate') is-invalid @enderror numeric"
                                                   required placeholder="0.00">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        @error('interest_rate')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Lock-in Period</label>
                                        <input type="text" name="lockin_period" v-model="lockin_period"
                                               class="form-control @error('lockin_period') is-invalid @enderror numeric"
                                               required placeholder="0">
                                        @error('lockin_period')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Period Type</label>
                                        <select class="form-control @error('lockin_type') is-invalid @enderror"
                                                name="lockin_type" v-model="lockin_type" required>
                                            <option value="">Select...</option>
                                            <option value="days">Days</option>
                                            <option value="weeks">Weeks</option>
                                            <option value="months">Months</option>
                                            <option value="years">Years</option>
                                        </select>
                                        @error('lockin_type')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="info-card" v-if="lockin_period > 0">
                                <i class="fas fa-lock"></i> 
                                <strong>Lock-in Notice:</strong> Withdrawals will be restricted for 
                                <strong>@{{ lockin_period }} @{{ lockin_type }}</strong> from account activation.
                            </div>
                        </div>

                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="fas fa-receipt"></i> Account Charges
                            </h4>
                            
                            <div v-if="selected_charges.length > 0" class="mb-3">
                                <div class="charge-badge" v-for="(charge, index) in selected_charges" :key="charge.id">
                                    @{{ charge.name }} - @{{ charge.amount }}
                                    <i class="fas fa-times-circle" @click="remove_charge(index)"></i>
                                    <input type="hidden" :name="'charges['+charge.id+']'" :value="charge.amount">
                                </div>
                            </div>
                            <div v-else class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No charges added yet. Select from available charges below.
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Add Charge</label>
                                        <select class="form-control" v-model="selected_charge">
                                            <option value="">Select a charge to add...</option>
                                            <option v-for="(charge, index) in savings_product_charges" :value="index">
                                                @{{ charge.charge.name }} - @{{ charge.charge.amount }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">&nbsp;</label>
                                    <button type="button" @click="add_charge" class="btn btn-success btn-block"
                                            :disabled="selected_charge === ''">
                                        <i class="fas fa-plus-circle"></i> Add Charge
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary btn-action" @click="prevStep">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary btn-action ml-2" @click="nextStep">
                                Review & Submit <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div v-show="step === 4">
                        <div class="account-summary">
                            <h4><i class="fas fa-clipboard-check"></i> Account Summary</h4>
                            
                            <div class="summary-item">
                                <span class="summary-label">Client:</span>
                                <span class="summary-value">@{{ selected_client ? selected_client.name : '-' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Product:</span>
                                <span class="summary-value">@{{ savings_product ? savings_product.name : '-' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Account Number:</span>
                                <span class="summary-value">@{{ account_number || 'Auto-generated' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Opening Balance:</span>
                                <span class="summary-value">@{{ automatic_opening_balance }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Interest Rate:</span>
                                <span class="summary-value">@{{ interest_rate }}% p.a.</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Lock-in Period:</span>
                                <span class="summary-value">@{{ lockin_period }} @{{ lockin_type }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Account Officer:</span>
                                <span class="summary-value">@{{ getOfficerName(savings_officer_id) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Charges Applied:</span>
                                <span class="summary-value">@{{ selected_charges.length }} charge(s)</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Submission Date:</span>
                                <span class="summary-value">@{{ submitted_on_date }}</span>
                            </div>
                        </div>

                        <div class="info-card success">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-check-circle"></i> Ready to Submit
                            </h6>
                            <p class="mb-0">Please review all details above. Once submitted, the account will be created and activated.</p>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary btn-action" @click="prevStep">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            <button type="submit" class="btn btn-success btn-action ml-2">
                                <i class="fas fa-check-circle mr-2"></i> Open Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                step: 1,
                client_id: parseInt("{{old('client_id')}}"),
                savings_officer_id: parseInt("{{old('savings_officer_id')}}"),
                savings_product_id: parseInt("{{old('savings_product_id')}}"),
                account_number: "{{old('account_number')}}",
                external_id: "{{old('external_id')}}",
                interest_rate: "{{old('interest_rate')}}",
                lockin_period: "{{old('lockin_period')}}",
                lockin_type: "{{old('lockin_type')}}",
                automatic_opening_balance: "{{old('automatic_opening_balance')}}",
                submitted_on_date: "{{old('submitted_on_date',date("Y-m-d"))}}",
                savings_products: savings_products,
                savings_product_charges: [],
                savings_charges: savings_charges,
                clients: [],
                users: users,
                selected_charge: '',
                savings_product: null,
                selected_client: null,
                selected_charges: []
            },
            methods: {
                nextStep() {
                    if (this.step < 4) {
                        this.step++;
                        window.scrollTo(0, 0);
                    }
                },
                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                        window.scrollTo(0, 0);
                    }
                },
                onSearch(search, loading) {
                    if (search.length) {
                        loading(true);
                        this.search(loading, search, this);
                    }
                },
                search: _.debounce((loading, search, vm) => {
                    axios.get('{{url('client/search')}}?s=' + search).then(function (response) {
                        vm.clients = response.data
                        loading(false);
                    }).catch(function (error) {
                        loading(false);
                    });
                }, 350),
                change_client() {
                    this.savings_officer_id = "";
                    this.selected_client = null;
                    if (this.client_id != "") {
                        this.clients.forEach(item => {
                            if (item.id == this.client_id) {
                                this.savings_officer_id = item.loan_officer_id;
                                this.selected_client = item;
                            }
                        })
                    }
                },
                change_product() {
                    this.savings_product = null;
                    if (this.savings_product_id != "") {
                        this.savings_products.forEach(item => {
                            if (item.id == this.savings_product_id) {
                                this.savings_product = item;
                                this.interest_rate = item.default_interest_rate;
                                this.automatic_opening_balance = item.automatic_opening_balance;
                                this.lockin_period = item.lockin_period;
                                this.lockin_type = item.lockin_type;
                                this.savings_product_charges = item.charges;
                                this.selected_charges = [];
                            }
                        })
                    }
                },
                add_charge() {
                    if (this.selected_charge !== '') {
                        const charge = this.savings_product_charges[this.selected_charge].charge;
                        // Check if already added
                        if (!this.selected_charges.find(c => c.id === charge.id)) {
                            this.selected_charges.push(charge);
                        }
                        this.selected_charge = '';
                    }
                },
                remove_charge(index) {
                    this.selected_charges.splice(index, 1);
                },
                getOfficerName(id) {
                    const officer = this.users.find(u => u.id === id);
                    return officer ? officer.full_name : '-';
                }
            }
        })
    </script>
@endsection
