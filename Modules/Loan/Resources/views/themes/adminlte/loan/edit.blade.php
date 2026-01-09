@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.edit',1) }} {{ trans_choice('loan::general.loan',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-edit"></i> {{ trans_choice('core::general.edit',1) }} {{ trans_choice('loan::general.loan',1) }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.edit',1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <form method="post" action="{{ url('loan/'.$loan->id.'/update') }}">
            {{csrf_field()}}
            <input type="hidden" name="loan_product_id" value="{{$loan->loan_product_id}}"/>
            <input type="hidden" name="client_id" value="{{$loan->client_id}}"/>
            <input type="hidden" name="client_type" value="{{$loan->client_type}}"/>
            
            <!-- Borrower & Product Info (Read-only) -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><strong>Loan Information</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Borrower:</strong> {{$loan->client->first_name}} {{$loan->client->last_name}}</p>
                            <p class="mb-0"><strong>Account:</strong> <span class="badge badge-info">{{$loan->client->account_number}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Loan Product:</strong> {{$loan->loan_product->name}}</p>
                            <p class="mb-0"><strong>Loan Duration:</strong> <span class="badge badge-success">{{$loan->loan_term}} {{$loan->loan_product->loan_term_type ?? 'months'}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Status:</strong> <span class="badge badge-warning">{{ucfirst($loan->status)}}</span></p>
                            <p class="mb-0"><strong>Applied On:</strong> {{$loan->submitted_on_date}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editable Loan Terms -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit mr-2"></i><strong>Edit Loan Terms</strong></h3>
                </div>
                <div class="card-body">
                    <!-- Principal & Fund -->
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
                                <small class="form-text text-muted">
                                    Range: @{{loan_product.minimum_principal}} - @{{loan_product.maximum_principal}}
                                </small>
                                @error('applied_amount')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Fund Source <span class="text-danger">*</span></label>
                                <v-select label="name" :options="funds" :reduce="fund => fund.id" v-model="fund_id">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search" v-bind="attributes" v-bind:required="!fund_id" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="fund_id" v-model="fund_id">
                            </div>
                        </div>
                    </div>

                    <!-- Loan Term (Read-only) -->
                    <div class="alert alert-info">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0"><i class="fas fa-calendar-check mr-2"></i><strong>Loan Duration:</strong> @{{loan_term}} @{{loan_term_type}}</h6>
                                <small class="text-muted">Fixed by loan product</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">Repayment schedule can be adjusted below</small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="loan_term" v-model="loan_term">
                    <input type="hidden" name="loan_term_type" v-model="loan_term_type">

                    <!-- Repayment Frequency -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Repayment Every <span class="text-danger">*</span></label>
                                <input type="number" name="repayment_frequency" class="form-control form-control-lg @error('repayment_frequency') is-invalid @enderror"
                                       v-model="repayment_frequency" required min="1" placeholder="e.g., 1, 2, 3">
                                @error('repayment_frequency')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">How often payments will be made</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Repayment Frequency <span class="text-danger">*</span></label>
                                <select class="form-control form-control-lg @error('repayment_frequency_type') is-invalid @enderror"
                                        name="repayment_frequency_type" v-model="repayment_frequency_type" required>
                                    <option value="">-- Select Frequency --</option>
                                    <option value="days">Daily</option>
                                    <option value="weeks">Weekly</option>
                                    <option value="months">Monthly</option>
                                </select>
                                @error('repayment_frequency_type')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Payment schedule type</small>
                            </div>
                        </div>
                    </div>

                    <!-- Interest Rate & Disbursement Date -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Interest Rate <span class="text-danger">*</span>
                                    <span class="badge badge-secondary">@{{loan_product.interest_rate_type}}</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="interest_rate" class="form-control @error('interest_rate') is-invalid @enderror"
                                           :readonly="loan_product.disallow_interest_rate_adjustment=='1'"
                                           v-model="interest_rate" step="0.01" min="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Range: @{{loan_product.minimum_interest_rate}}% - @{{loan_product.maximum_interest_rate}}%
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
                                            :config="{dateFormat: 'Y-m-d', altInput: true, altFormat: 'F j, Y'}"
                                            class="form-control @error('expected_disbursement_date') is-invalid @enderror"
                                            name="expected_disbursement_date" required>
                                </flat-pickr>
                                @error('expected_disbursement_date')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- First Payment Date -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Expected First Payment Date <span class="text-danger">*</span></label>
                                <flat-pickr v-model="expected_first_payment_date"
                                            :config="{dateFormat: 'Y-m-d', altInput: true, altFormat: 'F j, Y'}"
                                            class="form-control @error('expected_first_payment_date') is-invalid @enderror"
                                            name="expected_first_payment_date" required>
                                </flat-pickr>
                                @error('expected_first_payment_date')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">Date when the first repayment is expected</small>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Officer & Purpose -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Loan Officer</label>
                                <v-select label="full_name" :options="users" :reduce="user => user.id" v-model="loan_officer_id">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_officer_id" v-model="loan_officer_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Loan Purpose</label>
                                <v-select label="name" :options="loan_purposes" :reduce="purpose => purpose.id" v-model="loan_purpose_id">
                                    <template #search="{attributes, events}">
                                        <input autocomplete="off" class="vs__search" v-bind="attributes" v-on="events" />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_purpose_id" v-model="loan_purpose_id">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save mr-2"></i> Update Loan
                            </button>
                            <a href="{{url('loan/'.$loan->id.'/show')}}" class="btn btn-secondary btn-lg px-5 ml-2">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
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
                loan_product: {!! json_encode($loan->loan_product) !!},
                applied_amount: "{{old('applied_amount',$loan->applied_amount)}}",
                fund_id: parseInt("{{old('fund_id',$loan->fund_id)}}"),
                loan_term: "{{old('loan_term',$loan->loan_term)}}",
                loan_term_type: "{{old('loan_term_type',$loan->loan_product->loan_term_type ?? 'months')}}",
                repayment_frequency: "{{old('repayment_frequency',$loan->repayment_frequency)}}",
                repayment_frequency_type: "{{old('repayment_frequency_type',$loan->repayment_frequency_type)}}",
                interest_rate: "{{old('interest_rate',$loan->interest_rate)}}",
                expected_disbursement_date: "{{old('expected_disbursement_date',$loan->expected_disbursement_date)}}",
                expected_first_payment_date: "{{old('expected_first_payment_date',$loan->expected_first_payment_date)}}",
                loan_officer_id: parseInt("{{old('loan_officer_id',$loan->loan_officer_id)}}") || null,
                loan_purpose_id: parseInt("{{old('loan_purpose_id',$loan->loan_purpose_id)}}") || null,
                funds: {!! json_encode($funds) !!},
                users: {!! json_encode($users) !!},
                loan_purposes: {!! json_encode($loan_purposes) !!},
            },
            watch: {
                expected_disbursement_date(newVal) {
                    if (newVal && this.repayment_frequency && this.repayment_frequency_type) {
                        this.calculateFirstPaymentDate();
                    }
                },
                repayment_frequency(newVal) {
                    if (newVal && this.expected_disbursement_date && this.repayment_frequency_type) {
                        this.calculateFirstPaymentDate();
                    }
                },
                repayment_frequency_type(newVal) {
                    if (newVal && this.expected_disbursement_date && this.repayment_frequency) {
                        this.calculateFirstPaymentDate();
                    }
                }
            },
            methods: {
                calculateFirstPaymentDate() {
                    if (!this.expected_disbursement_date || !this.repayment_frequency || !this.repayment_frequency_type) {
                        return;
                    }
                    
                    let disbursementDate = new Date(this.expected_disbursement_date);
                    let frequency = parseInt(this.repayment_frequency);
                    let frequencyType = this.repayment_frequency_type;
                    
                    // Calculate first payment date based on frequency
                    if (frequencyType === 'days') {
                        disbursementDate.setDate(disbursementDate.getDate() + frequency);
                    } else if (frequencyType === 'weeks') {
                        disbursementDate.setDate(disbursementDate.getDate() + (frequency * 7));
                    } else if (frequencyType === 'months') {
                        disbursementDate.setMonth(disbursementDate.getMonth() + frequency);
                    }
                    
                    // Format date as YYYY-MM-DD for the date input
                    let year = disbursementDate.getFullYear();
                    let month = String(disbursementDate.getMonth() + 1).padStart(2, '0');
                    let day = String(disbursementDate.getDate()).padStart(2, '0');
                    
                    this.expected_first_payment_date = `${year}-${month}-${day}`;
                }
            }
        });
    </script>
@endsection
