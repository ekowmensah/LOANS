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
                        {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.loan',1) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em>
                            <span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.loan',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <!-- Step Indicator -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="step-indicator" :class="{active: stage >= 1, complete: stage > 1}">
                                <div class="step-number">1</div>
                                <div class="step-label">Client Selection</div>
                            </div>
                            <div class="step-line" :class="{active: stage > 1}"></div>
                            <div class="step-indicator" :class="{active: stage >= 2, complete: stage > 2}">
                                <div class="step-number">2</div>
                                <div class="step-label">Loan Product</div>
                            </div>
                            <div class="step-line" :class="{active: stage > 2}"></div>
                            <div class="step-indicator" :class="{active: stage >= 3}">
                                <div class="step-number">3</div>
                                <div class="step-label">Loan Terms</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ url('loan/store') }}">
            {{csrf_field()}}
            
            <!-- Step 1: Client Selection -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Step 1: Client Selection</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_type" class="control-label font-weight-bold">Loan Type <span class="text-danger">*</span></label>
                                <select class="form-control form-control-lg @error('client_type') is-invalid @enderror"
                                        name="client_type" id="client_type" v-model="client_type" required>
                                    <option value="">-- Select Loan Type --</option>
                                    <option value="client"><i class="fas fa-user"></i> Individual Loan</option>
                                    <option value="group"><i class="fas fa-users"></i> Group Loan</option>
                                </select>
                                @error('client_type')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Individual Client Search - Inline -->
                        <div class="col-md-5" v-if="client_type === 'client'">
                            <div class="form-group">
                                <label for="client_search_input" class="control-label font-weight-bold">Search Client <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="client_search_input" v-model="client_search" 
                                           placeholder="Enter savings account number (e.g., S0000001)" @keyup.enter="searchClient">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary px-4" type="button" @click="searchClient" :disabled="searching_client">
                                            <span v-if="!searching_client"><i class="fas fa-search"></i> Search Client</span>
                                            <span v-else><i class="fas fa-spinner fa-spin"></i> Searching...</span>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="client_id" v-model="client_id" :required="client_type === 'client'">
                                @error('client_id')
                                <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Enter savings account number</small>
                            </div>
                        </div>
                        
                        <!-- Group Selection - Inline -->
                        <div class="col-md-4" v-if="client_type === 'group'">
                            <div class="form-group">
                                <label for="group_id" class="control-label font-weight-bold">Select Group <span class="text-danger">*</span></label>
                                <select class="form-control form-control-lg @error('group_id') is-invalid @enderror" 
                                        name="group_id" v-model="group_id"
                                        id="group_id" :required="client_type === 'group'">
                                    <option value="">-- Select a Group --</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Select group</small>
                            </div>
                        </div>
                        
                        <!-- Loan Product - Inline -->
                        <div class="col-md-4" v-if="(client_type === 'client' && client) || (client_type === 'group' && group_id)">
                            <div class="form-group">
                                <label for="loan_product_id"
                                       class="control-label">{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.product',1)}}</label>
                                <v-select label="name" :options="loan_products"
                                          :reduce="loan_product => loan_product.id"
                                          v-on:input="change_loan_product"
                                          v-model="loan_product_id">
                                    <template #search="{attributes, events}">
                                        <input
                                                autocomplete="off"
                                                class="vs__search @error('loan_product_id') is-invalid @enderror"
                                                v-bind="attributes"
                                                v-bind:required="!loan_product_id"
                                                v-on="events"
                                        />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_product_id"
                                       v-model="loan_product_id">
                                @error('loan_product_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Client Details Card - Full Width Below -->
                    <div class="row mt-3" v-if="client">
                        <div class="col-md-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-check-circle"></i> Client Found</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5 class="mb-2"><i class="fas fa-user"></i> @{{ client.first_name }} @{{ client.last_name }}</h5>
                                            <p class="mb-1"><strong>Account Number:</strong> @{{ client.account_number }} | <strong>Savings Account:</strong> <span class="badge badge-primary">@{{ client.savings_account_number }}</span> | <strong>Mobile:</strong> <i class="fas fa-phone"></i> @{{ client.mobile || 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <img v-if="client.photo" :src="client.photo" class="img-thumbnail rounded-circle" style="width: 60px; height: 60px; object-fit: cover;" alt="Client Photo">
                                            <div v-else class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-user fa-2x text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 & 3: Loan Product and Terms -->
            <div v-show="loan_product" class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar"></i> Step 2 & 3: Loan Details</h5>
                        </div>
                        <div class="card-body">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-coins"></i> Loan Terms</h5>
                            <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applied_amount"
                                           class="control-label">{{trans_choice('loan::general.principal',1)}}</label>
                                    <input type="number" name="applied_amount"
                                           id="applied_amount"
                                           class="form-control @error('applied_amount') is-invalid @enderror numeric"
                                           v-model="applied_amount" required>
                                    @error('applied_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fund_id"
                                           class="control-label">{{trans_choice('loan::general.fund',1)}}</label>
                                    <v-select label="name" :options="funds"
                                              :reduce="fund => fund.id"
                                              v-model="fund_id">
                                        <template #search="{attributes, events}">
                                            <input
                                                    autocomplete="off"
                                                    class="vs__search @error('fund_id') is-invalid @enderror"
                                                    v-bind="attributes"
                                                    v-bind:required="!fund_id"
                                                    v-on="events"
                                            />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="fund_id"
                                           v-model="fund_id">
                                    @error('fund_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="loan_term"
                                           class="control-label">{{trans_choice('loan::general.loan',1)}}  {{trans_choice('loan::general.term',1)}}</label>
                                    <input type="text" name="loan_term"
                                           id="loan_term"
                                           class="form-control @error('loan_term') is-invalid @enderror numeric"
                                           v-model="loan_term" required>
                                    @error('loan_term')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="repayment_frequency"
                                           class="control-label">{{trans_choice('loan::general.repayment',1)}} {{trans_choice('loan::general.frequency',1)}}</label>
                                    <input type="text" name="repayment_frequency"
                                           id="repayment_frequency" v-model="repayment_frequency"
                                           class="form-control @error('repayment_frequency') is-invalid @enderror numeric"
                                           required>
                                    @error('repayment_frequency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="repayment_frequency_type"
                                           class="control-label">{{trans_choice('core::general.type',1)}}</label>
                                    <select class="form-control  @error('repayment_frequency_type') is-invalid @enderror"
                                            name="repayment_frequency_type"
                                            v-model="repayment_frequency_type" id="repayment_frequency_type"
                                            required>
                                        <option value=""></option>
                                        <option value="days">{{trans_choice('loan::general.day',2)}}</option>
                                        <option value="weeks">{{trans_choice('loan::general.week',2)}}</option>
                                        <option value="months">{{trans_choice('loan::general.month',2)}}</option>
                                    </select>
                                    @error('repayment_frequency_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="form-group" v-if="loan_product">
                                    <label for="interest_rate"
                                           class="control-label">
                                        {{trans_choice('loan::general.interest',1)}} {{trans_choice('loan::general.rate',1)}}
                                        <span v-if="loan_product.interest_rate_type=='month'">
                                        (% {{trans_choice('loan::general.per',1)}} {{trans_choice('loan::general.month',1)}})
                                            </span>

                                        <span v-if="loan_product.interest_rate_type=='year'">
                                        (% {{trans_choice('loan::general.per',1)}} {{trans_choice('loan::general.year',1)}}
                                        )
                                        </span>
                                    </label>
                                    <input type="text" name="interest_rate"
                                           :readonly="loan_product.disallow_interest_rate_adjustment=='1'"
                                           id="interest_rate" v-model="interest_rate"
                                           class="form-control @error('interest_rate') is-invalid @enderror text">
                                    @error('interest_rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expected_disbursement_date"
                                           class="control-label">{{trans_choice('loan::general.expected',1)}} {{trans_choice('loan::general.disbursement',1)}} {{trans_choice('core::general.date',1)}}</label>
                                    <flat-pickr
                                            v-model="expected_disbursement_date"
                                            class="form-control  @error('expected_disbursement_date') is-invalid @enderror"
                                            name="expected_disbursement_date" id="expected_disbursement_date" required>
                                    </flat-pickr>
                                    @error('expected_disbursement_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @foreach($custom_fields as $custom_field)
                                <?php
                                $field = custom_field_build_form_field($custom_field);
                                ?>
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @if($custom_field->type=='radio')
                                            <label class="control-label"
                                                   for="field_{{$custom_field->id}}">{{$field['label']}}</label>
                                            {!! $field['html'] !!}
                                        @else
                                            <label class="control-label"
                                                   for="field_{{$custom_field->id}}">{{$field['label']}}</label>
                                            {!! $field['html'] !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <h3>{{trans_choice('core::general.setting',2)}}</h3>
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="loan_officer_id"
                                           class="control-label">{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.officer',1)}}</label>
                                    <v-select label="full_name" :options="users"
                                              :reduce="user => user.id"
                                              v-model="loan_officer_id">
                                        <template #search="{attributes, events}">
                                            <input
                                                    autocomplete="off"
                                                    class="vs__search @error('loan_officer_id') is-invalid @enderror"
                                                    v-bind="attributes"
                                                    v-bind:required="!loan_officer_id"
                                                    v-on="events"
                                            />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="loan_officer_id"
                                           v-model="loan_officer_id">
                                    @error('loan_officer_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="loan_purpose_id"
                                           class="control-label">{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.purpose',1)}}</label>
                                    <v-select label="name" :options="loan_purposes"
                                              :reduce="loan_purpose => loan_purpose.id"
                                              v-model="loan_purpose_id">
                                        <template #search="{attributes, events}">
                                            <input
                                                    autocomplete="off"
                                                    class="vs__search @error('loan_purpose_id') is-invalid @enderror"
                                                    v-bind="attributes"
                                                    v-bind:required="!loan_purpose_id"
                                                    v-on="events"
                                            />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="loan_purpose_id"
                                           v-model="loan_purpose_id">
                                    @error('loan_purpose_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expected_first_payment_date"
                                           class="control-label">{{trans_choice('loan::general.expected',1)}} {{trans_choice('loan::general.first_payment_date',1)}}</label>
                                    <flat-pickr
                                            v-model="expected_first_payment_date"
                                            class="form-control  @error('expected_first_payment_date') is-invalid @enderror"
                                            name="expected_first_payment_date" id="expected_first_payment_date"
                                            required>
                                    </flat-pickr>
                                    @error('expected_first_payment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h3>{{trans_choice('loan::general.charge',2)}}</h3>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{trans_choice('core::general.name',1)}}</th>
                                <th>{{trans_choice('core::general.type',1)}}</th>
                                <th>{{trans_choice('core::general.amount',1)}}</th>
                                <th>{{trans_choice('loan::general.collected_on',1)}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="charges_table_body">
                            <tr v-for="(charge,index) in selected_charges" v-bind:id="charge.charge.id">
                                <td>@{{ charge.charge.name }}</td>
                                <td>
                                    <span v-if="charge.charge.loan_charge_option_id==1">{{trans_choice('loan::general.flat', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==2">{{trans_choice('loan::general.principal_due_on_installment', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==3">{{trans_choice('loan::general.principal_interest_due_on_installment', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==4">{{trans_choice('loan::general.interest_due_on_installment', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==5">{{trans_choice('loan::general.total_outstanding_loan_principal', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==6">{{trans_choice('loan::general.percentage_of_original_loan_principal_per_installment', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_option_id==7">{{trans_choice('loan::general.original_loan_principal', 1)}}</span>
                                </td>
                                <td>
                                <span v-if="charge.charge.allow_override=='0'">
                                    <input v-bind:name="'charges['+charge.charge.id+']'" type="hidden"
                                           v-bind:value="charge.charge.amount">
                                    @{{ charge.charge.amount }}
                                </span>
                                    <span v-if="charge.charge.allow_override=='1'">
                                    <input v-bind:name="'charges['+charge.charge.id+']'" type="text"
                                           class="form-control numeric" v-bind:value="charge.charge.amount" required>
                                </span>
                                </td>
                                <td>
                                    <span v-if="charge.charge.loan_charge_type_id==1">{{trans_choice('loan::general.disbursement', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==2">{{trans_choice('loan::general.specified_due_date', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==3">{{trans_choice('loan::general.installment', 1) . ' ' . trans_choice('loan::general.fee', 2)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==4">{{trans_choice('loan::general.overdue', 1) . ' ' . trans_choice('loan::general.installment', 1) . ' ' . trans_choice('loan::general.fee', 2)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==5">{{trans_choice('loan::general.disbursement_paid_with_repayment', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==6">{{trans_choice('loan::general.loan_rescheduling_fee', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==7">{{trans_choice('loan::general.overdue_on_loan_maturity', 1)}}</span>
                                    <span v-if="charge.charge.loan_charge_type_id==8">{{trans_choice('loan::general.last_installment_fee', 1)}}</span>

                                </td>
                                <td><i class="fa fa-remove" v-on:click="remove_charge" v-bind:data-id="index"></i></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loan_charges"
                                           class="control-label">{{trans_choice('loan::general.charge',2)}}</label>
                                    <select class="form-control @error('loan_charges') is-invalid @enderror"
                                            name="loan_charges"
                                            id="loan_charges" v-model="selected_charge">
                                        <option value=""></option>
                                        <option v-for="(charge,index) in loan_product_charges" v-bind:value="index">
                                            @{{charge.charge.name }}
                                        </option>
                                    </select>
                                    @error('loan_charges')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label"></label>
                                <button type="button" v-on:click="add_charge"
                                        class="btn btn-info"
                                        style="margin-top:20px">{{trans_choice('core::general.add',1)}} {{trans_choice('core::general.to',1)}} {{trans_choice('loan::general.product',1)}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-check-circle"></i> {{trans_choice('core::general.save',1)}} Loan Application
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Calculation Summary Sidebar -->
            <div class="col-md-4">
                <div class="calc-summary-card">
                    <h5 class="mb-3">
                        <i class="fas fa-calculator"></i> 
                        <span v-if="!isGroupLoan">Loan Summary</span>
                        <span v-else>Group Loan Summary</span>
                    </h5>
                    
                    <!-- Group Info -->
                    <div v-if="isGroupLoan && selectedGroup" class="mb-3 p-2" style="background: rgba(255,255,255,0.15); border-radius: 6px;">
                        <small>
                            <i class="fas fa-users"></i> <strong>@{{ selectedGroup.name }}</strong><br>
                            <i class="fas fa-user-friends"></i> @{{ groupMemberCount }} Members
                        </small>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">
                            <span v-if="!isGroupLoan">Principal Amount:</span>
                            <span v-else>Total Principal:</span>
                        </span>
                        <span class="calc-value">@{{ applied_amount ? parseFloat(applied_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00' }}</span>
                    </div>
                    
                    <!-- Per Member Principal (Group Only) -->
                    <div v-if="isGroupLoan && groupMemberCount > 0" class="calc-row" style="font-size: 0.9em; opacity: 0.9;">
                        <span class="calc-label">└─ Per Member:</span>
                        <span class="calc-value">@{{ perMemberAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">Interest Rate:</span>
                        <span class="calc-value">@{{ interest_rate || 0 }}%</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">Loan Term:</span>
                        <span class="calc-value">@{{ loan_term || 0 }} @{{ repayment_frequency_type || 'months' }}</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">Total Interest:</span>
                        <span class="calc-value">@{{ totalInterest.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <!-- Per Member Interest (Group Only) -->
                    <div v-if="isGroupLoan && groupMemberCount > 0" class="calc-row" style="font-size: 0.9em; opacity: 0.9;">
                        <span class="calc-label">└─ Per Member:</span>
                        <span class="calc-value">@{{ perMemberInterest.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">Total Repayable:</span>
                        <span class="calc-value">@{{ totalRepayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <!-- Per Member Repayable (Group Only) -->
                    <div v-if="isGroupLoan && groupMemberCount > 0" class="calc-row" style="font-size: 0.9em; opacity: 0.9;">
                        <span class="calc-label">└─ Per Member:</span>
                        <span class="calc-value">@{{ perMemberRepayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">Number of Installments:</span>
                        <span class="calc-value">@{{ numberOfInstallments }}</span>
                    </div>
                    
                    <div class="calc-row">
                        <span class="calc-label">
                            <span v-if="!isGroupLoan">Installment Amount:</span>
                            <span v-else>Total Installment:</span>
                        </span>
                        <span class="calc-value">@{{ installmentAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <!-- Per Member Installment (Group Only) -->
                    <div v-if="isGroupLoan && groupMemberCount > 0" class="calc-row" style="font-size: 0.9em; opacity: 0.9;">
                        <span class="calc-label">└─ Per Member:</span>
                        <span class="calc-value">@{{ perMemberInstallment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
                    </div>
                    
                    <div class="mt-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 8px;">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            <span v-if="!isGroupLoan">Calculations are estimates based on simple interest.</span>
                            <span v-else>Group loan amounts are divided equally among @{{ groupMemberCount }} members. Each member is responsible for their allocated portion.</span>
                            Actual amounts may vary based on loan product settings and charges.
                        </small>
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
                stage: 1,
                client_search: '',
                searching_client: false,
                client: null,
                client_type: "{{old('client_type')}}",
                loan_product: "{{old('loan_product')}}",
                loan_product_id: parseInt("{{old('loan_product_id')}}"),
                client_id: parseInt("{{old('client_id')}}"),
                group_id: parseInt("{{old('group_id')}}"),
                applied_amount: "{{old('applied_amount')}}",
                loan_term: "{{old('loan_term')}}",
                repayment_frequency: "{{old('repayment_frequency')}}",
                repayment_frequency_type: "{{old('repayment_frequency_type')}}",
                fund_id: parseInt("{{old('fund_id')}}"),
                interest_rate: "{{old('interest_rate')}}",
                expected_disbursement_date: "{{old('expected_disbursement_date',date("Y-m-d"))}}",
                loan_officer_id: parseInt("{{old('loan_officer_id')}}"),
                expected_first_payment_date: "{{old('expected_first_payment_date',\Illuminate\Support\Carbon::today()->addMonths(1)->format("Y-m-d"))}}",
                loan_purpose_id: parseInt("{{old('loan_purpose_id')}}"),
                loan_charges: loan_charges,
                loan_product_charges: [],
                loan_products: loan_products,
                clients: clients,
                groups: groups,
                funds: funds,
                loan_purposes: loan_purposes,
                users: users,
                selected_charge: "",
                selected_charges: []

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
                    
                    // Simple interest calculation
                    if (this.loan_product && this.loan_product.interest_rate_type === 'year') {
                        return principal * rate * (term / 12);
                    } else {
                        return principal * rate * term;
                    }
                },
                totalRepayable() {
                    return parseFloat(this.applied_amount || 0) + this.totalInterest;
                },
                numberOfInstallments() {
                    if (!this.loan_term || !this.repayment_frequency) return 0;
                    return Math.ceil(parseFloat(this.loan_term) / parseFloat(this.repayment_frequency));
                },
                installmentAmount() {
                    if (this.numberOfInstallments === 0) return 0;
                    return this.totalRepayable / this.numberOfInstallments;
                },
                // Group loan calculations
                selectedGroup() {
                    if (!this.isGroupLoan || !this.group_id) return null;
                    return this.groups.find(g => g.id == this.group_id);
                },
                groupMemberCount() {
                    if (!this.selectedGroup) return 0;
                    // Assuming groups have a members_count or members array
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
                }
            },
            created: function () {


            },
            methods: {
                searchClient() {
                    if (!this.client_search) {
                        alert('Please enter savings account number');
                        return;
                    }

                    this.searching_client = true;
                    this.client = null;
                    this.client_id = '';

                    axios.post('{{url("loan/search-client")}}', {
                        search: this.client_search
                    })
                    .then(response => {
                        this.searching_client = false;
                        if (response.data.success) {
                            this.client = response.data.data;
                            this.client_id = this.client.id;
                            this.loan_officer_id = this.client.loan_officer_id || '';
                        } else {
                            alert(response.data.message || 'Client not found');
                        }
                    })
                    .catch(error => {
                        this.searching_client = false;
                        console.error(error);
                        if (error.response && error.response.data && error.response.data.message) {
                            alert(error.response.data.message);
                        } else {
                            alert('Error searching for client. Please try again.');
                        }
                    });
                },
                add_charge(event) {

                    this.selected_charges.push(this.loan_product_charges[this.selected_charge]);
                    //delete charges[this.selected_charge];
                    this.selected_charge = "";

                },
                remove_charge(event) {
                    var id = event.currentTarget.getAttribute('data-id');
                    this.selected_charges.splice(id, 1);
                    //charges.push(original_charges[id]);
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
                }
            }
        });
    </script>
    <style>
        /* Scope all styles to loan creation page only */
        #app .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }
        
        #app .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        
        #app .step-indicator.active .step-number {
            background: #007bff;
            color: white;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
        }
        
        #app .step-indicator.complete .step-number {
            background: #28a745;
            color: white;
        }
        
        #app .step-indicator.complete .step-number::after {
            content: '✓';
            position: absolute;
        }
        
        #app .step-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
            text-align: center;
        }
        
        #app .step-indicator.active .step-label {
            color: #007bff;
            font-weight: 600;
        }
        
        #app .step-line {
            height: 2px;
            background: #e9ecef;
            flex: 1;
            margin: 0 10px;
            margin-top: -30px;
            transition: all 0.3s ease;
        }
        
        #app .step-line.active {
            background: #28a745;
        }
        
        /* Card Enhancements - scoped */
        #app .card.shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075) !important;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        #app .card-header.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        #app .card-header.bg-success {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }
        
        /* Form Enhancements - scoped */
        #app .form-control-lg {
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }
        
        #app .input-group-lg .input-group-text {
            font-size: 1.1rem;
        }
        
        /* Calculation Summary Styles */
        #app .calc-summary-card {
            position: sticky;
            top: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
        }
        
        #app .calc-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        #app .calc-row:last-child {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: bold;
            padding-top: 15px;
        }
        
        #app .calc-label {
            font-weight: 500;
        }
        
        #app .calc-value {
            font-weight: 600;
        }
        
        /* Animation - scoped */
        @keyframes loanCardFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #app .card {
            animation: loanCardFadeIn 0.3s ease;
        }
    </style>
@endsection