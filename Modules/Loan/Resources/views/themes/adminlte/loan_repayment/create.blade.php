@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.repayment',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.repayment',1) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('loan/'.$id.'/show')}}">{{ trans_choice('loan::general.loan',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.repayment',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form method="post" action="{{ url('loan/'.$id.'/repayment/store') }}">
                    {{csrf_field()}}
                    
                    <!-- Payment Source Selection -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4"><i class="fas fa-hand-holding-usd text-primary"></i> Payment Method</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="payment-option" :class="{'active': payment_source === 'cash'}" @click="payment_source = 'cash'">
                                        <input type="radio" name="payment_source" value="cash" v-model="payment_source" required hidden>
                                        <div class="text-center p-4">
                                            <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                                            <h6 class="mb-0">Cash Payment</h6>
                                            <small class="text-muted">Pay with cash or bank transfer</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="payment-option" :class="{'active': payment_source === 'savings'}" @click="payment_source = 'savings'">
                                        <input type="radio" name="payment_source" value="savings" v-model="payment_source" required hidden>
                                        <div class="text-center p-4">
                                            <i class="fas fa-piggy-bank fa-3x text-info mb-3"></i>
                                            <h6 class="mb-0">Savings Account</h6>
                                            <small class="text-muted">Balance: <strong>{{ number_format($savings_balance ?? 0, 2) }}</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4"><i class="fas fa-file-invoice-dollar text-primary"></i> Payment Details</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Amount</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="number" step="0.01" name="amount" v-model="amount"
                                                   class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                                   placeholder="0.00" required>
                                        </div>
                                        @error('amount')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Payment Date</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <flat-pickr v-model="date" name="date"
                                                       class="form-control form-control-lg @error('date') is-invalid @enderror" required>
                                            </flat-pickr>
                                        </div>
                                        @error('date')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Cash Payment Type -->
                            <div v-show="payment_source === 'cash'" class="mt-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Payment Type</label>
                                    <v-select label="name" :options="payment_types"
                                              :reduce="payment_type => payment_type.id"
                                              v-model="payment_type_id"
                                              placeholder="Select payment type"
                                              class="form-control-lg">
                                        <template #search="{attributes, events}">
                                            <input autocomplete="off"
                                                   :required="payment_source === 'cash' && !payment_type_id"
                                                   class="vs__search"
                                                   v-bind="attributes"
                                                   v-on="events" />
                                        </template>
                                    </v-select>
                                    <input type="hidden" name="payment_type_id" v-model="payment_type_id">
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="form-group mt-3">
                                <label class="font-weight-bold">Notes (Optional)</label>
                                <textarea name="description" v-model="description"
                                          class="form-control" rows="3"
                                          placeholder="Add any additional notes about this payment..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between">
                                <button type="button" onclick="window.history.back()" class="btn btn-lg btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-lg btn-primary px-5">
                                    <i class="fas fa-check-circle"></i> Process Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('styles')
    <style>
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
        }
        .payment-option:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.15);
            transform: translateY(-2px);
        }
        .payment-option.active {
            border-color: #007bff;
            background: #f0f8ff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
        }
        .card.shadow-sm {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
            border: none;
            margin-bottom: 1.5rem;
        }
        .input-group-lg .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .input-group-lg .form-control {
            border-left: none;
        }
        .input-group-lg .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
    </style>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                payment_source: "{{old('payment_source', 'cash')}}",
                amount: "{{old('amount')}}",
                date: "{{old('date',date('Y-m-d'))}}",
                payment_type_id: parseInt("{{old('payment_type_id')}}"),
                account_number: "{{old('account_number')}}",
                cheque_number: "{{old('cheque_number')}}",
                routing_code: "{{old('routing_code')}}",
                receipt: "{{old('receipt')}}",
                bank_name: "{{old('bank_name')}}",
                description: `{{old('description')}}`,
                payment_types: {!! json_encode($payment_types) !!},

            },
            created: function () {

            },
            methods: {
                onSubmit() {

                }
            }
        });
    </script>
@endsection
