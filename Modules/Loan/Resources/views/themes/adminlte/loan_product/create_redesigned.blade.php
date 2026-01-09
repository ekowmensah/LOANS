@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.product',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-box-open"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.product',1) }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan/product')}}">{{ trans_choice('loan::general.product',2) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add',1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <form method="post" action="{{ url('loan/product/store') }}">
            {{csrf_field()}}
            
            <!-- SECTION 1: Basic Information -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><strong>Basic Information</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       v-model="name" required placeholder="e.g., Personal Loan">
                                @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror"
                                       v-model="short_name" required placeholder="e.g., PL">
                                @error('short_name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Description <span class="text-danger">*</span></label>
                                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                       v-model="description" required placeholder="Brief description">
                                @error('description')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Fund <span class="text-danger">*</span></label>
                                <v-select label="name" :options="funds" :reduce="fund => fund.id" v-model="fund_id" placeholder="Select fund...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search @error('fund_id') is-invalid @enderror"
                                               :required="!fund_id" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="fund_id" v-model="fund_id">
                                @error('fund_id')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Currency <span class="text-danger">*</span></label>
                                <v-select label="name" :options="currencies" :reduce="currency => currency.id" v-model="currency_id" placeholder="Select currency...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search @error('currency_id') is-invalid @enderror"
                                               :required="!currency_id" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="currency_id" v-model="currency_id">
                                @error('currency_id')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Decimal Places</label>
                                <input type="number" name="decimals" class="form-control @error('decimals') is-invalid @enderror" 
                                       v-model="decimals" min="0" max="4" value="2">
                                @error('decimals')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Number of decimal places for amounts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Principal Amount Settings -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-dollar-sign mr-2"></i><strong>Principal Amount Settings</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Default Principal <span class="text-danger">*</span></label>
                                <input type="number" name="default_principal" class="form-control @error('default_principal') is-invalid @enderror"
                                       v-model="default_principal" required step="0.01" min="0">
                                @error('default_principal')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Default loan amount</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Minimum Principal <span class="text-danger">*</span></label>
                                <input type="number" name="minimum_principal" class="form-control @error('minimum_principal') is-invalid @enderror"
                                       v-model="minimum_principal" required step="0.01" min="0">
                                @error('minimum_principal')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Minimum allowed amount</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Maximum Principal <span class="text-danger">*</span></label>
                                <input type="number" name="maximum_principal" class="form-control @error('maximum_principal') is-invalid @enderror"
                                       v-model="maximum_principal" required step="0.01" min="0">
                                @error('maximum_principal')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Maximum allowed amount</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Loan Term Settings -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i><strong>Loan Term Settings</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Default Loan Term <span class="text-danger">*</span></label>
                                <input type="number" name="default_loan_term" class="form-control @error('default_loan_term') is-invalid @enderror"
                                       v-model="default_loan_term" required min="1">
                                @error('default_loan_term')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Default duration</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Minimum Loan Term <span class="text-danger">*</span></label>
                                <input type="number" name="minimum_loan_term" class="form-control @error('minimum_loan_term') is-invalid @enderror"
                                       v-model="minimum_loan_term" required min="1">
                                @error('minimum_loan_term')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Minimum duration</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Maximum Loan Term <span class="text-danger">*</span></label>
                                <input type="number" name="maximum_loan_term" class="form-control @error('maximum_loan_term') is-invalid @enderror"
                                       v-model="maximum_loan_term" required min="1">
                                @error('maximum_loan_term')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Maximum duration</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Repayment Settings -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-redo-alt mr-2"></i><strong>Repayment Settings</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Repayment Frequency <span class="text-danger">*</span></label>
                                <input type="number" name="repayment_frequency" class="form-control @error('repayment_frequency') is-invalid @enderror"
                                       v-model="repayment_frequency" required min="1" placeholder="e.g., 1">
                                @error('repayment_frequency')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">How often repayments are made</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Frequency Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('repayment_frequency_type') is-invalid @enderror"
                                        name="repayment_frequency_type" v-model="repayment_frequency_type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                </select>
                                @error('repayment_frequency_type')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Unit of frequency</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Interest Rate Settings -->
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-percentage mr-2"></i><strong>Interest Rate Settings</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Default Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" name="default_interest_rate" class="form-control @error('default_interest_rate') is-invalid @enderror"
                                       v-model="default_interest_rate" required step="0.01" min="0">
                                @error('default_interest_rate')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Minimum Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" name="minimum_interest_rate" class="form-control @error('minimum_interest_rate') is-invalid @enderror"
                                       v-model="minimum_interest_rate" required step="0.01" min="0">
                                @error('minimum_interest_rate')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Maximum Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" name="maximum_interest_rate" class="form-control @error('maximum_interest_rate') is-invalid @enderror"
                                       v-model="maximum_interest_rate" required step="0.01" min="0">
                                @error('maximum_interest_rate')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Rate Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('interest_rate_type') is-invalid @enderror"
                                        name="interest_rate_type" v-model="interest_rate_type" required>
                                    <option value="">-- Select --</option>
                                    <option value="month">Per Month</option>
                                    <option value="year">Per Year</option>
                                    <option value="principal">Flat (Principal)</option>
                                </select>
                                @error('interest_rate_type')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Lock Interest Rate?</label>
                                <select class="form-control @error('disallow_interest_rate_adjustment') is-invalid @enderror"
                                        name="disallow_interest_rate_adjustment" v-model="disallow_interest_rate_adjustment" required>
                                    <option value="0">No - Allow Adjustment</option>
                                    <option value="1">Yes - Lock Rate</option>
                                </select>
                                @error('disallow_interest_rate_adjustment')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Prevent loan officers from changing the interest rate</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Deduct Interest from Principal?</label>
                                <select class="form-control @error('deduct_interest_from_principal') is-invalid @enderror"
                                        name="deduct_interest_from_principal" v-model="deduct_interest_from_principal" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('deduct_interest_from_principal')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Deduct interest upfront from disbursement</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: Grace Period & Calculation Methods -->
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calculator mr-2"></i><strong>Grace Period & Calculation Methods</strong></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold border-bottom pb-2 mb-3">Grace Periods</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Grace on Principal Paid</label>
                                <input type="number" name="grace_on_principal_paid" class="form-control @error('grace_on_principal_paid') is-invalid @enderror"
                                       v-model="grace_on_principal_paid" value="0" min="0">
                                @error('grace_on_principal_paid')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Number of installments</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Grace on Interest Paid</label>
                                <input type="number" name="grace_on_interest_paid" class="form-control @error('grace_on_interest_paid') is-invalid @enderror"
                                       v-model="grace_on_interest_paid" value="0" min="0">
                                @error('grace_on_interest_paid')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Number of installments</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Grace on Interest Charged</label>
                                <input type="number" name="grace_on_interest_charged" class="form-control @error('grace_on_interest_charged') is-invalid @enderror"
                                       v-model="grace_on_interest_charged" value="0" min="0">
                                @error('grace_on_interest_charged')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Number of installments</small>
                            </div>
                        </div>
                    </div>

                    <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Calculation Methods</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Interest Methodology <span class="text-danger">*</span></label>
                                <select class="form-control @error('interest_methodology') is-invalid @enderror"
                                        name="interest_methodology" v-model="interest_methodology" required>
                                    <option value="">-- Select --</option>
                                    <option value="flat">Flat Rate</option>
                                    <option value="declining_balance">Declining Balance</option>
                                </select>
                                @error('interest_methodology')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Amortization Method <span class="text-danger">*</span></label>
                                <select class="form-control @error('amortization_method') is-invalid @enderror"
                                        name="amortization_method" v-model="amortization_method" required>
                                    <option value="">-- Select --</option>
                                    <option value="equal_installments">Equal Installments</option>
                                    <option value="equal_principal_payments">Equal Principal Payments</option>
                                </select>
                                @error('amortization_method')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Transaction Processing Strategy <span class="text-danger">*</span></label>
                                <v-select label="name" :options="loan_transaction_processing_strategies"
                                          :reduce="strategy => strategy.id" v-model="loan_transaction_processing_strategy_id"
                                          placeholder="Select strategy...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search @error('loan_transaction_processing_strategy_id') is-invalid @enderror"
                                               :required="!loan_transaction_processing_strategy_id" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_transaction_processing_strategy_id" v-model="loan_transaction_processing_strategy_id">
                                @error('loan_transaction_processing_strategy_id')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 7: Charges & Credit Checks -->
            <div class="card card-outline card-purple">
                <div class="card-header bg-purple">
                    <h3 class="card-title"><i class="fas fa-tags mr-2"></i><strong>Charges & Credit Checks</strong></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Loan Charges</label>
                                <v-select label="name" :options="available_charges" :reduce="charge => charge.id"
                                          v-model="selected_charges" multiple placeholder="Select charges...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="charges" v-model="selected_charges">
                                <small class="form-text text-muted">Optional fees and charges for this product</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Credit Checks</label>
                                <v-select label="translated_name" :options="credit_checks" :reduce="check => check.id"
                                          v-model="selected_credit_checks" multiple placeholder="Select credit checks...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="credit_checks" v-model="selected_credit_checks">
                                <small class="form-text text-muted">Required credit checks before approval</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 8: Accounting (Conditional) -->
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book mr-2"></i><strong>Accounting Settings</strong></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Accounting Rule <span class="text-danger">*</span></label>
                                <select class="form-control @error('accounting_rule') is-invalid @enderror"
                                        name="accounting_rule" v-model="accounting_rule" required>
                                    <option value="none">None - No Accounting</option>
                                    <option value="cash">Cash Based Accounting</option>
                                </select>
                                @error('accounting_rule')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div v-if="accounting_rule==='cash'" class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Cash Accounting Enabled</strong> - Configure chart of accounts below
                        </div>

                        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Asset Accounts</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Fund Source</label>
                                    <v-select label="name" :options="assets" :reduce="asset => asset.id"
                                              v-model="fund_source_chart_of_account_id" placeholder="Select account...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !fund_source_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="fund_source_chart_of_account_id" v-model="fund_source_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Loan Portfolio</label>
                                    <v-select label="name" :options="assets" :reduce="asset => asset.id"
                                              v-model="loan_portfolio_chart_of_account_id" placeholder="Select account...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !loan_portfolio_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="loan_portfolio_chart_of_account_id" v-model="loan_portfolio_chart_of_account_id">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Suspended Income</label>
                                    <v-select label="name" :options="assets" :reduce="asset => asset.id"
                                              v-model="suspended_income_chart_of_account_id" placeholder="Select account...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !suspended_income_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="suspended_income_chart_of_account_id" v-model="suspended_income_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Overpayments (Liability)</label>
                                    <v-select label="name" :options="liabilities" :reduce="liability => liability.id"
                                              v-model="overpayments_chart_of_account_id" placeholder="Select account...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !overpayments_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="overpayments_chart_of_account_id" v-model="overpayments_chart_of_account_id">
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Income Accounts</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Income from Interest</label>
                                    <v-select label="name" :options="income" :reduce="inc => inc.id"
                                              v-model="income_from_interest_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !income_from_interest_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="income_from_interest_chart_of_account_id" v-model="income_from_interest_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Income from Penalties</label>
                                    <v-select label="name" :options="income" :reduce="inc => inc.id"
                                              v-model="income_from_penalties_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !income_from_penalties_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="income_from_penalties_chart_of_account_id" v-model="income_from_penalties_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Income from Fees</label>
                                    <v-select label="name" :options="income" :reduce="inc => inc.id"
                                              v-model="income_from_fees_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !income_from_fees_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="income_from_fees_chart_of_account_id" v-model="income_from_fees_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Income from Recovery</label>
                                    <v-select label="name" :options="income" :reduce="inc => inc.id"
                                              v-model="income_from_recovery_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !income_from_recovery_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="income_from_recovery_chart_of_account_id" v-model="income_from_recovery_chart_of_account_id">
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Expense & Write-off Accounts</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Losses Written Off</label>
                                    <v-select label="name" :options="expenses" :reduce="expense => expense.id"
                                              v-model="losses_written_off_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !losses_written_off_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="losses_written_off_chart_of_account_id" v-model="losses_written_off_chart_of_account_id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Interest Written Off</label>
                                    <v-select label="name" :options="income" :reduce="inc => inc.id"
                                              v-model="interest_written_off_chart_of_account_id" placeholder="Select...">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off" class="vs__search" v-bind:required="accounting_rule==='cash' && !interest_written_off_chart_of_account_id"
                                                   v-bind="attributes" v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="interest_written_off_chart_of_account_id" v-model="interest_written_off_chart_of_account_id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 9: Additional Settings -->
            <div class="card card-outline card-teal">
                <div class="card-header bg-teal">
                    <h3 class="card-title"><i class="fas fa-cogs mr-2"></i><strong>Additional Settings</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Exclude Weekends?</label>
                                <select class="form-control @error('exclude_weekends') is-invalid @enderror"
                                        name="exclude_weekends" v-model="exclude_weekends" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('exclude_weekends')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Skip weekends in repayment schedule</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Exclude Holidays?</label>
                                <select class="form-control @error('exclude_holidays') is-invalid @enderror"
                                        name="exclude_holidays" v-model="exclude_holidays" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                @error('exclude_holidays')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Skip holidays in repayment schedule</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Auto Disburse?</label>
                                <select class="form-control @error('auto_disburse') is-invalid @enderror"
                                        name="auto_disburse" v-model="auto_disburse" required>
                                    <option value="0">No - Manual Disbursement</option>
                                    <option value="1">Yes - Auto Disburse on Approval</option>
                                </select>
                                @error('auto_disburse')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Automatically disburse when approved</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Active Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('active') is-invalid @enderror"
                                        name="active" v-model="active" required>
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                                @error('active')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Enable this product for use</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{url('loan/product')}}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg ml-2">
                                <i class="fas fa-save mr-2"></i> Save Loan Product
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
                name: "{{old('name')}}",
                short_name: "{{old('short_name')}}",
                description: "{{old('description')}}",
                fund_id: parseInt("{{old('fund_id')}}") || null,
                currency_id: parseInt("{{old('currency_id')}}") || null,
                decimals: "{{old('decimals',2)}}",
                minimum_principal: "{{old('minimum_principal')}}",
                default_principal: "{{old('default_principal')}}",
                maximum_principal: "{{old('maximum_principal')}}",
                minimum_loan_term: "{{old('minimum_loan_term')}}",
                default_loan_term: "{{old('default_loan_term')}}",
                maximum_loan_term: "{{old('maximum_loan_term')}}",
                repayment_frequency: "{{old('repayment_frequency')}}",
                repayment_frequency_type: "{{old('repayment_frequency_type')}}",
                minimum_interest_rate: "{{old('minimum_interest_rate')}}",
                default_interest_rate: "{{old('default_interest_rate')}}",
                maximum_interest_rate: "{{old('maximum_interest_rate')}}",
                interest_rate_type: "{{old('interest_rate_type')}}",
                grace_on_principal_paid: "{{old('grace_on_principal_paid',0)}}",
                grace_on_interest_paid: "{{old('grace_on_interest_paid',0)}}",
                grace_on_interest_charged: "{{old('grace_on_interest_charged',0)}}",
                interest_methodology: "{{old('interest_methodology')}}",
                amortization_method: "{{old('amortization_method')}}",
                auto_disburse: "{{old('auto_disburse',0)}}",
                selected_credit_checks: [],
                selected_charges: [],
                accounting_rule: "{{old('accounting_rule','none')}}",
                deduct_interest_from_principal: "{{old('deduct_interest_from_principal',0)}}",
                disallow_interest_rate_adjustment: "{{old('disallow_interest_rate_adjustment',0)}}",
                active: "{{old('active',1)}}",
                exclude_weekends: "{{old('exclude_weekends',0)}}",
                exclude_holidays: "{{old('exclude_holidays',1)}}",
                loan_transaction_processing_strategy_id: parseInt("{{old('loan_transaction_processing_strategy_id')}}") || null,
                fund_source_chart_of_account_id: parseInt("{{old('fund_source_chart_of_account_id')}}") || null,
                loan_portfolio_chart_of_account_id: parseInt("{{old('loan_portfolio_chart_of_account_id')}}") || null,
                overpayments_chart_of_account_id: parseInt("{{old('overpayments_chart_of_account_id')}}") || null,
                suspended_income_chart_of_account_id: parseInt("{{old('suspended_income_chart_of_account_id')}}") || null,
                income_from_interest_chart_of_account_id: parseInt("{{old('income_from_interest_chart_of_account_id')}}") || null,
                income_from_penalties_chart_of_account_id: parseInt("{{old('income_from_penalties_chart_of_account_id')}}") || null,
                income_from_fees_chart_of_account_id: parseInt("{{old('income_from_fees_chart_of_account_id')}}") || null,
                income_from_recovery_chart_of_account_id: parseInt("{{old('income_from_recovery_chart_of_account_id')}}") || null,
                losses_written_off_chart_of_account_id: parseInt("{{old('losses_written_off_chart_of_account_id')}}") || null,
                interest_written_off_chart_of_account_id: parseInt("{{old('interest_written_off_chart_of_account_id')}}") || null,
                funds: {!! json_encode($funds) !!},
                currencies: {!! json_encode($currencies) !!},
                credit_checks: {!! json_encode($credit_checks) !!},
                loan_transaction_processing_strategies: {!! json_encode($loan_transaction_processing_strategies) !!},
                assets: {!! json_encode($assets) !!},
                liabilities: {!! json_encode($liabilities) !!},
                income: {!! json_encode($income) !!},
                expenses: {!! json_encode($expenses) !!},
                loan_charges: {!! json_encode($loan_charges) !!},
            },
            computed: {
                available_charges: function () {
                    return this.loan_charges.filter(item => {
                        if (this.currency_id == item.currency_id) {
                            return true;
                        }
                        return false;
                    })
                }
            }
        })
    </script>

    <style>
        .card-outline {
            border-top: 3px solid;
        }
        .bg-purple {
            background-color: #6f42c1 !important;
            color: white !important;
        }
        .card-purple {
            border-color: #6f42c1 !important;
        }
        .bg-teal {
            background-color: #20c997 !important;
            color: white !important;
        }
        .card-teal {
            border-color: #20c997 !important;
        }
    </style>
@endsection
