@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.loan',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-hand-holding-usd"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.loan',1) }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add',1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <form method="post" action="{{ url('loan/store') }}">
            {{csrf_field()}}
            
            <div class="row">
                <!-- Main Form Area (Left Side) -->
                <div class="col-lg-8">
                    
                    <!-- STEP 1: Borrower Information -->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-circle mr-2"></i><strong>Step 1:</strong> Borrower Information</h3>
                            <div class="card-tools">
                                <span class="badge badge-primary">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Loan Type Selection -->
                            <div class="form-group">
                                <label class="font-weight-bold">Loan Type <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio custom-control-inline w-100">
                                            <input type="radio" id="type_individual" name="client_type" value="client" 
                                                   class="custom-control-input" v-model="client_type" required>
                                            <label class="custom-control-label w-100 p-3 border rounded" for="type_individual" style="cursor: pointer;">
                                                <i class="fas fa-user fa-2x text-primary d-block mb-2"></i>
                                                <strong>Individual Loan</strong>
                                                <small class="d-block text-muted">Loan for a single borrower</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio custom-control-inline w-100">
                                            <input type="radio" id="type_group" name="client_type" value="group" 
                                                   class="custom-control-input" v-model="client_type" required>
                                            <label class="custom-control-label w-100 p-3 border rounded" for="type_group" style="cursor: pointer;">
                                                <i class="fas fa-users fa-2x text-success d-block mb-2"></i>
                                                <strong>Group Loan</strong>
                                                <small class="d-block text-muted">Loan for a group of borrowers</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Client Search -->
                            <div v-if="client_type === 'client'" class="mt-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Search Client by Savings Account <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control" v-model="client_search" 
                                               placeholder="Enter savings account number (e.g., S0000001)" 
                                               @keyup.enter="searchClient">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary px-4" type="button" @click="searchClient" :disabled="searching_client">
                                                <span v-if="!searching_client"><i class="fas fa-search mr-1"></i> Search</span>
                                                <span v-else><i class="fas fa-spinner fa-spin mr-1"></i> Searching...</span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="client_id" v-model="client_id" :required="client_type === 'client'">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Enter the client's savings account number to find their profile
                                    </small>
                                </div>

                                <!-- Client Found Card -->
                                <div v-if="client" class="alert alert-success border-success mt-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            <img v-if="client.photo" :src="client.photo" class="img-thumbnail rounded-circle" 
                                                 style="width: 80px; height: 80px; object-fit: cover;" alt="Client Photo">
                                            <div v-else class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-user fa-3x text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <h5 class="mb-2"><i class="fas fa-check-circle text-success"></i> Client Found</h5>
                                            <p class="mb-1"><strong>Name:</strong> @{{ client.first_name }} @{{ client.last_name }}</p>
                                            <p class="mb-1">
                                                <strong>Account:</strong> <span class="badge badge-info">@{{ client.account_number }}</span> | 
                                                <strong>Savings:</strong> <span class="badge badge-primary">@{{ client.savings_account_number }}</span>
                                            </p>
                                            <p class="mb-0"><strong>Mobile:</strong> <i class="fas fa-phone"></i> @{{ client.mobile || 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Group Selection -->
                            <div v-if="client_type === 'group'" class="mt-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Select Group <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-lg @error('group_id') is-invalid @enderror" 
                                            name="group_id" v-model="group_id" :required="client_type === 'group'">
                                        <option value="">-- Select a Group --</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Choose the group applying for the loan
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Loan Product Selection -->
                    <div v-show="(client_type === 'client' && client) || (client_type === 'group' && group_id)" 
                         class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-box-open mr-2"></i><strong>Step 2:</strong> Loan Product</h3>
                            <div class="card-tools">
                                <span class="badge badge-info">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Select Loan Product <span class="text-danger">*</span></label>
                                <v-select label="name" :options="loan_products"
                                          :reduce="loan_product => loan_product.id"
                                          v-on:input="change_loan_product"
                                          v-model="loan_product_id"
                                          placeholder="Choose a loan product...">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off"
                                               class="vs__search @error('loan_product_id') is-invalid @enderror"
                                               v-bind="attributes"
                                               v-bind:required="!loan_product_id"
                                               v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_product_id" v-model="loan_product_id">
                                @error('loan_product_id')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Select the type of loan product for this application
                                </small>
                            </div>

                            <!-- Loan Product Details Preview -->
                            <div v-if="loan_product" class="alert alert-info mt-3">
                                <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Product Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Interest Rate:</strong> @{{ loan_product.default_interest_rate }}% 
                                            <span v-if="loan_product.interest_rate_type=='month'">per month</span>
                                            <span v-if="loan_product.interest_rate_type=='year'">per year</span>
                                            <span v-if="loan_product.interest_rate_type=='principal'">flat</span>
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Repayment:</strong> Every @{{ loan_product.repayment_frequency }} 
                                            @{{ loan_product.repayment_frequency_type }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Loan Terms & Details -->
                    <div v-show="loan_product" class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-2"></i><strong>Step 3:</strong> Loan Terms & Details</h3>
                            <div class="card-tools">
                                <span class="badge badge-success">Required</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Loan Amount & Fund -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Principal Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="number" name="applied_amount" class="form-control @error('applied_amount') is-invalid @enderror"
                                                   v-model="applied_amount" required step="0.01" min="0">
                                        </div>
                                        <small class="form-text text-muted" v-if="loan_product">
                                            Range: @{{ loan_product.minimum_principal }} - @{{ loan_product.maximum_principal }}
                                        </small>
                                        @error('applied_amount')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Fund Source <span class="text-danger">*</span></label>
                                        <v-select label="name" :options="funds"
                                                  :reduce="fund => fund.id"
                                                  v-model="fund_id"
                                                  placeholder="Select fund...">
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search @error('fund_id') is-invalid @enderror"
                                                       v-bind="attributes"
                                                       v-bind:required="!fund_id"
                                                       v-on="events" />
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="fund_id" v-model="fund_id">
                                        @error('fund_id')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Loan Term & Repayment -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Loan Term <span class="text-danger">*</span></label>
                                        <input type="number" name="loan_term" class="form-control @error('loan_term') is-invalid @enderror"
                                               v-model="loan_term" required min="1">
                                        <small class="form-text text-muted" v-if="loan_product">
                                            Range: @{{ loan_product.minimum_loan_term }} - @{{ loan_product.maximum_loan_term }}
                                        </small>
                                        @error('loan_term')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Repayment Every <span class="text-danger">*</span></label>
                                        <input type="number" name="repayment_frequency" class="form-control @error('repayment_frequency') is-invalid @enderror"
                                               v-model="repayment_frequency" required min="1">
                                        @error('repayment_frequency')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Frequency Type <span class="text-danger">*</span></label>
                                        <select class="form-control @error('repayment_frequency_type') is-invalid @enderror"
                                                name="repayment_frequency_type" v-model="repayment_frequency_type" required>
                                            <option value="">Select...</option>
                                            <option value="days">Days</option>
                                            <option value="weeks">Weeks</option>
                                            <option value="months">Months</option>
                                        </select>
                                        @error('repayment_frequency_type')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Interest Rate & Disbursement Date -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" v-if="loan_product">
                                        <label class="font-weight-bold">
                                            Interest Rate <span class="text-danger">*</span>
                                            <span class="badge badge-secondary" v-if="loan_product.interest_rate_type=='month'">% per month</span>
                                            <span class="badge badge-secondary" v-if="loan_product.interest_rate_type=='year'">% per year</span>
                                            <span class="badge badge-secondary" v-if="loan_product.interest_rate_type=='principal'">% flat</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="interest_rate" class="form-control @error('interest_rate') is-invalid @enderror"
                                                   :readonly="loan_product.disallow_interest_rate_adjustment=='1'"
                                                   v-model="interest_rate" step="0.01" min="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted" v-if="loan_product">
                                            Range: @{{ loan_product.minimum_interest_rate }}% - @{{ loan_product.maximum_interest_rate }}%
                                        </small>
                                        @error('interest_rate')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Expected Disbursement Date <span class="text-danger">*</span></label>
                                        <flat-pickr v-model="expected_disbursement_date"
                                                    class="form-control @error('expected_disbursement_date') is-invalid @enderror"
                                                    name="expected_disbursement_date" required>
                                        </flat-pickr>
                                        @error('expected_disbursement_date')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Additional Settings -->
                    <div v-show="loan_product" class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog mr-2"></i><strong>Step 4:</strong> Additional Settings</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Loan Officer <span class="text-danger">*</span></label>
                                        <v-select label="full_name" :options="users"
                                                  :reduce="user => user.id"
                                                  v-model="loan_officer_id"
                                                  placeholder="Select officer...">
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search @error('loan_officer_id') is-invalid @enderror"
                                                       v-bind="attributes"
                                                       v-bind:required="!loan_officer_id"
                                                       v-on="events" />
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="loan_officer_id" v-model="loan_officer_id">
                                        @error('loan_officer_id')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Loan Purpose</label>
                                        <v-select label="name" :options="loan_purposes"
                                                  :reduce="loan_purpose => loan_purpose.id"
                                                  v-model="loan_purpose_id"
                                                  placeholder="Select purpose...">
                                            <template #search="{attributes, events}">
                                                <input autocomplete="off"
                                                       class="vs__search"
                                                       v-bind="attributes"
                                                       v-on="events" />
                                            </template>
                                        </v-select>
                                        <input type="hidden" name="loan_purpose_id" v-model="loan_purpose_id">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">First Payment Date <span class="text-danger">*</span></label>
                                        <flat-pickr v-model="expected_first_payment_date"
                                                    class="form-control @error('expected_first_payment_date') is-invalid @enderror"
                                                    name="expected_first_payment_date" required>
                                        </flat-pickr>
                                        @error('expected_first_payment_date')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Fields -->
                            @foreach($custom_fields as $custom_field)
                                <?php $field = custom_field_build_form_field($custom_field); ?>
                                <div class="form-group">
                                    <label class="font-weight-bold">{{$field['label']}}</label>
                                    {!! $field['html'] !!}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- STEP 5: Charges -->
                    <div v-show="loan_product" class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-receipt mr-2"></i><strong>Step 5:</strong> Loan Charges (Optional)</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Add Charge</label>
                                <select class="form-control" v-model="selected_charge">
                                    <option value="">-- Select a charge to add --</option>
                                    <option v-for="(charge,index) in loan_product_charges" v-bind:value="index">
                                        @{{charge.charge.name }} - @{{charge.charge.amount}}
                                    </option>
                                </select>
                            </div>

                            <div class="table-responsive" v-if="selected_charges.length > 0">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Charge Name</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Collected On</th>
                                            <th width="50">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(charge,index) in selected_charges" v-bind:id="charge.charge.id">
                                            <td>@{{ charge.charge.name }}</td>
                                            <td>
                                                <span v-if="charge.charge.loan_charge_option_id==1">Flat</span>
                                                <span v-if="charge.charge.loan_charge_option_id==2">Principal Due</span>
                                                <span v-if="charge.charge.loan_charge_option_id==3">Principal + Interest Due</span>
                                                <span v-if="charge.charge.loan_charge_option_id==4">Interest Due</span>
                                                <span v-if="charge.charge.loan_charge_option_id==5">Total Outstanding</span>
                                                <span v-if="charge.charge.loan_charge_option_id==6">% of Original Principal</span>
                                                <span v-if="charge.charge.loan_charge_option_id==7">Original Principal</span>
                                            </td>
                                            <td>
                                                <span v-if="charge.charge.allow_override=='0'">
                                                    <input v-bind:name="'charges['+charge.charge.id+']'" type="hidden" v-bind:value="charge.charge.amount">
                                                    @{{ charge.charge.amount }}
                                                </span>
                                                <span v-if="charge.charge.allow_override=='1'">
                                                    <input v-bind:name="'charges['+charge.charge.id+']'" type="number" 
                                                           class="form-control form-control-sm" v-bind:value="charge.charge.amount" required>
                                                </span>
                                            </td>
                                            <td>
                                                <span v-if="charge.charge.loan_charge_type_id==1">Disbursement</span>
                                                <span v-if="charge.charge.loan_charge_type_id==2">Specified Due Date</span>
                                                <span v-if="charge.charge.loan_charge_type_id==3">Installment Fee</span>
                                                <span v-if="charge.charge.loan_charge_type_id==4">Overdue Installment</span>
                                                <span v-if="charge.charge.loan_charge_type_id==5">Disbursement Paid with Repayment</span>
                                                <span v-if="charge.charge.loan_charge_type_id==6">Loan Rescheduling Fee</span>
                                                <span v-if="charge.charge.loan_charge_type_id==7">Overdue on Maturity</span>
                                                <span v-if="charge.charge.loan_charge_type_id==8">Last Installment Fee</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger" v-on:click="remove_charge" v-bind:data-id="index">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No charges added yet. Select a charge from the dropdown above to add it.
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Loan Summary Sidebar (Right Side) -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <!-- Loan Summary Card -->
                        <div class="card card-primary card-outline">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title"><i class="fas fa-calculator mr-2"></i><strong>Loan Summary</strong></h3>
                            </div>
                            <div class="card-body">
                                <div v-if="!loan_product" class="text-center text-muted py-5">
                                    <i class="fas fa-chart-pie fa-3x mb-3 d-block"></i>
                                    <p>Select a loan product to see the summary</p>
                                </div>

                                <div v-else>
                                    <!-- Individual Loan Summary -->
                                    <div v-if="!isGroupLoan">
                                        <div class="summary-item">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Principal Amount</span>
                                                <h5 class="mb-0 font-weight-bold">@{{ formatCurrency(applied_amount) }}</h5>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="summary-item">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Total Interest</span>
                                                <h5 class="mb-0 text-info">@{{ formatCurrency(totalInterest) }}</h5>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="summary-item bg-light p-3 rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="font-weight-bold">Total Repayable</span>
                                                <h4 class="mb-0 text-success font-weight-bold">@{{ formatCurrency(totalRepayable) }}</h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="summary-item">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Number of Installments</span>
                                                <h5 class="mb-0">@{{ numberOfInstallments }}</h5>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="summary-item bg-primary text-white p-3 rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="font-weight-bold">Per Installment</span>
                                                <h4 class="mb-0 font-weight-bold">@{{ formatCurrency(installmentAmount) }}</h4>
                                            </div>
                                            <small class="d-block mt-2">
                                                Every @{{ repayment_frequency }} @{{ repayment_frequency_type }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Group Loan Summary -->
                                    <div v-else>
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-users"></i> <strong>Group Loan</strong>
                                            <div class="mt-2">Members: @{{ groupMemberCount }}</div>
                                        </div>

                                        <h6 class="font-weight-bold mb-3">Total Loan</h6>
                                        <div class="summary-item">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Principal</span>
                                                <span class="font-weight-bold">@{{ formatCurrency(applied_amount) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Interest</span>
                                                <span class="text-info">@{{ formatCurrency(totalInterest) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="font-weight-bold">Total Repayable</span>
                                                <span class="text-success font-weight-bold">@{{ formatCurrency(totalRepayable) }}</span>
                                            </div>
                                        </div>

                                        <hr>

                                        <h6 class="font-weight-bold mb-3">Per Member</h6>
                                        <div class="summary-item bg-light p-3 rounded">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Principal</span>
                                                <span>@{{ formatCurrency(perMemberAmount) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Interest</span>
                                                <span>@{{ formatCurrency(perMemberInterest) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="font-weight-bold">Total</span>
                                                <span class="font-weight-bold">@{{ formatCurrency(perMemberRepayable) }}</span>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="summary-item bg-primary text-white p-3 rounded">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="font-weight-bold">Per Member Installment</span>
                                                <h5 class="mb-0 font-weight-bold">@{{ formatCurrency(perMemberInstallment) }}</h5>
                                            </div>
                                            <small class="d-block mt-2">
                                                @{{ numberOfInstallments }} payments every @{{ repayment_frequency }} @{{ repayment_frequency_type }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Loan Details -->
                                    <div class="mt-4">
                                        <h6 class="font-weight-bold border-bottom pb-2">Loan Details</h6>
                                        <small class="d-block mb-1"><strong>Product:</strong> @{{ loan_product.name }}</small>
                                        <small class="d-block mb-1"><strong>Interest Rate:</strong> @{{ interest_rate }}% 
                                            <span v-if="loan_product.interest_rate_type=='month'">per month</span>
                                            <span v-if="loan_product.interest_rate_type=='year'">per year</span>
                                            <span v-if="loan_product.interest_rate_type=='principal'">flat</span>
                                        </small>
                                        <small class="d-block mb-1"><strong>Loan Term:</strong> @{{ loan_term }} @{{ repayment_frequency_type }}</small>
                                        <small class="d-block mb-1"><strong>Repayment:</strong> Every @{{ repayment_frequency }} @{{ repayment_frequency_type }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-success btn-lg btn-block" :disabled="!loan_product">
                                    <i class="fas fa-check-circle mr-2"></i> Submit Loan Application
                                </button>
                                <a href="{{url('loan')}}" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-question-circle mr-2"></i>Need Help?</h3>
                            </div>
                            <div class="card-body">
                                <small>
                                    <p class="mb-2"><strong>Steps to create a loan:</strong></p>
                                    <ol class="pl-3 mb-0" style="font-size: 0.85rem;">
                                        <li>Select loan type (Individual or Group)</li>
                                        <li>Search for client or select group</li>
                                        <li>Choose a loan product</li>
                                        <li>Enter loan terms and details</li>
                                        <li>Review summary and submit</li>
                                    </ol>
                                </small>
                            </div>
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
                client_type: "",
                client_id: "",
                group_id: "",
                loan_product_id: "",
                applied_amount: "",
                fund_id: "",
                loan_term: "",
                repayment_frequency: "",
                repayment_frequency_type: "",
                interest_rate: "",
                expected_disbursement_date: "",
                expected_first_payment_date: "",
                loan_officer_id: "",
                loan_purpose_id: "",
                client_search: "",
                searching_client: false,
                client: null,
                loan_product: null,
                selected_charge: "",
                selected_charges: [],
                loan_product_charges: [],
                stage: 1,
                loan_products: {!! json_encode($loan_products) !!},
                funds: {!! json_encode($funds) !!},
                users: {!! json_encode($users) !!},
                loan_purposes: {!! json_encode($loan_purposes) !!},
                groups: {!! json_encode($groups) !!},
            },
            computed: {
                isGroupLoan() {
                    return this.client_type === 'group';
                },
                totalInterest() {
                    if (!this.applied_amount || !this.interest_rate || !this.loan_term) return 0;
                    let principal = parseFloat(this.applied_amount);
                    let rate = parseFloat(this.interest_rate) / 100;
                    let term = parseFloat(this.loan_term);
                    
                    let termInMonths = term;
                    if (this.repayment_frequency_type === 'days') {
                        termInMonths = term / 30;
                    } else if (this.repayment_frequency_type === 'weeks') {
                        termInMonths = term / 4.33;
                    }
                    
                    if (this.loan_product && this.loan_product.interest_rate_type === 'year') {
                        return principal * rate * (termInMonths / 12);
                    } else if (this.loan_product && this.loan_product.interest_rate_type === 'principal') {
                        return principal * rate;
                    } else {
                        return principal * rate * termInMonths;
                    }
                },
                totalRepayable() {
                    return parseFloat(this.applied_amount || 0) + this.totalInterest;
                },
                numberOfInstallments() {
                    if (!this.loan_term || !this.repayment_frequency) return 0;
                    let term = parseFloat(this.loan_term);
                    let frequency = parseFloat(this.repayment_frequency);
                    return Math.ceil(term / frequency);
                },
                installmentAmount() {
                    if (this.numberOfInstallments === 0) return 0;
                    return this.totalRepayable / this.numberOfInstallments;
                },
                selectedGroup() {
                    if (!this.isGroupLoan || !this.group_id) return null;
                    return this.groups.find(g => g.id == this.group_id);
                },
                groupMemberCount() {
                    if (!this.selectedGroup) return 0;
                    return this.selectedGroup.members_count || this.selectedGroup.members?.length || 0;
                },
                perMemberAmount() {
                    if (!this.isGroupLoan || this.groupMemberCount === 0) return 0;
                    return parseFloat(this.applied_amount || 0) / this.groupMemberCount;
                },
                perMemberInterest() {
                    if (!this.isGroupLoan || this.groupMemberCount === 0) return 0;
                    return this.totalInterest / this.groupMemberCount;
                },
                perMemberRepayable() {
                    if (!this.isGroupLoan || this.groupMemberCount === 0) return 0;
                    return this.totalRepayable / this.groupMemberCount;
                },
                perMemberInstallment() {
                    if (!this.isGroupLoan || this.groupMemberCount === 0) return 0;
                    return this.installmentAmount / this.groupMemberCount;
                }
            },
            watch: {
                client_type(newVal) {
                    this.stage = newVal ? 1 : 1;
                },
                client(newVal) {
                    if (newVal) this.stage = 2;
                },
                group_id(newVal) {
                    if (newVal) this.stage = 2;
                },
                loan_product_id(newVal) {
                    if (newVal) this.stage = 3;
                },
                applied_amount(newVal) {
                    if (this.loan_product && newVal) {
                        this.$nextTick(() => this.validatePrincipal());
                    }
                },
                loan_term(newVal) {
                    if (this.loan_product && newVal) {
                        this.$nextTick(() => this.validateLoanTerm());
                    }
                },
                interest_rate(newVal) {
                    if (this.loan_product && newVal) {
                        this.$nextTick(() => this.validateInterestRate());
                    }
                },
                selected_charge(newVal) {
                    if (newVal !== "") {
                        this.add_charge();
                    }
                }
            },
            methods: {
                searchClient() {
                    if (!this.client_search) {
                        alert('Please enter savings account number');
                        return;
                    }
                    this.searching_client = true;
                    axios.get('{{ url('loan/search_client') }}', {
                        params: {
                            savings_account_number: this.client_search
                        }
                    }).then(response => {
                        this.searching_client = false;
                        if (response.data) {
                            this.client = response.data;
                            this.client_id = response.data.id;
                            this.loan_officer_id = response.data.loan_officer_id;
                        } else {
                            alert('Client not found');
                        }
                    }).catch(error => {
                        this.searching_client = false;
                        alert('Error searching for client');
                    });
                },
                change_loan_product() {
                    if (this.loan_product_id != "") {
                        this.loan_products.forEach(item => {
                            if (item.id == this.loan_product_id) {
                                this.loan_product = item;
                                this.applied_amount = this.loan_product.default_principal;
                                this.loan_term = this.loan_product.default_loan_term;
                                this.repayment_frequency = this.loan_product.repayment_frequency;
                                this.repayment_frequency_type = this.loan_product.repayment_frequency_type;
                                this.fund_id = this.loan_product.fund_id;
                                this.interest_rate = this.loan_product.default_interest_rate;
                                this.loan_product_charges = this.loan_product.charges;
                                
                                this.validatePrincipal();
                                this.validateLoanTerm();
                                this.validateInterestRate();
                            }
                        })
                    }
                },
                change_client() {
                    this.loan_officer_id = "";
                    if (this.client_id != "") {
                        this.clients.forEach(item => {
                            if (item.id == this.client_id) {
                                this.loan_officer_id = item.loan_officer_id;
                            }
                        })
                    }
                },
                validatePrincipal() {
                    if (!this.loan_product) return;
                    let amount = parseFloat(this.applied_amount);
                    let min = parseFloat(this.loan_product.minimum_principal);
                    let max = parseFloat(this.loan_product.maximum_principal);
                    
                    if (amount < min) {
                        this.applied_amount = min;
                        alert(`Principal amount must be at least ${min.toLocaleString()}`);
                    } else if (amount > max) {
                        this.applied_amount = max;
                        alert(`Principal amount cannot exceed ${max.toLocaleString()}`);
                    }
                },
                validateLoanTerm() {
                    if (!this.loan_product) return;
                    let term = parseFloat(this.loan_term);
                    let min = parseFloat(this.loan_product.minimum_loan_term);
                    let max = parseFloat(this.loan_product.maximum_loan_term);
                    
                    if (term < min) {
                        this.loan_term = min;
                        alert(`Loan term must be at least ${min}`);
                    } else if (term > max) {
                        this.loan_term = max;
                        alert(`Loan term cannot exceed ${max}`);
                    }
                },
                validateInterestRate() {
                    if (!this.loan_product) return;
                    if (this.loan_product.disallow_interest_rate_adjustment == '1') return;
                    
                    let rate = parseFloat(this.interest_rate);
                    let min = parseFloat(this.loan_product.minimum_interest_rate);
                    let max = parseFloat(this.loan_product.maximum_interest_rate);
                    
                    if (rate < min) {
                        this.interest_rate = min;
                        alert(`Interest rate must be at least ${min}%`);
                    } else if (rate > max) {
                        this.interest_rate = max;
                        alert(`Interest rate cannot exceed ${max}%`);
                    }
                },
                add_charge() {
                    if (this.selected_charge !== "") {
                        this.selected_charges.push(this.loan_product_charges[this.selected_charge]);
                        this.selected_charge = "";
                    }
                },
                remove_charge(e) {
                    this.selected_charges.splice(e.target.dataset.id, 1);
                },
                formatCurrency(value) {
                    if (!value) return '0.00';
                    return parseFloat(value).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        });
    </script>

    <style>
        .sticky-top {
            position: sticky;
            z-index: 1020;
        }
        
        .summary-item {
            margin-bottom: 0.5rem;
        }
        
        .card-outline {
            border-top: 3px solid;
        }
        
        .custom-control-label {
            transition: all 0.3s ease;
        }
        
        .custom-control-input:checked ~ .custom-control-label {
            background-color: #f0f8ff;
            border-color: #007bff !important;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection
