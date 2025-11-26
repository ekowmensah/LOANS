@extends('core::layouts.master')
@section('title')
    {{ trans_choice('teller::general.teller', 1) }}
@endsection
@section('content')
    <div class="nk-content-body" id="app">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ trans_choice('teller::general.teller', 1) }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>{{ trans_choice('teller::general.search_account', 1) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="nk-block">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="account_number">{{ trans_choice('savings::general.account_number', 1) }}</label>
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="account_number" v-model="account_number" 
                                               placeholder="Enter account number" @keyup.enter="searchAccount">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="searchAccount" :disabled="searching">
                                                <em class="icon ni ni-search"></em> {{ trans_choice('core::general.search', 1) }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details Section -->
                    <div v-if="account" class="mt-4">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <h5 class="card-title">{{ trans_choice('savings::general.account', 1) }} {{ trans_choice('core::general.details', 2) }}</h5>
                                <div class="row g-4 mt-2">
                                    <div class="col-lg-2 text-center">
                                        <div class="user-avatar sq lg">
                                            <img v-if="account.client_photo" :src="'/uploads/' + account.client_photo" alt="Client Photo">
                                            <em v-else class="icon ni ni-user-alt"></em>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('client::general.client', 1) }}</label>
                                                    <div class="form-control-plaintext"><strong>@{{ account.client_name }}</strong></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('client::general.mobile', 1) }}</label>
                                                    <div class="form-control-plaintext">@{{ account.client_mobile }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('savings::general.account_number', 1) }}</label>
                                                    <div class="form-control-plaintext">@{{ account.account_number }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('savings::general.product', 1) }}</label>
                                                    <div class="form-control-plaintext">@{{ account.product_name }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('branch::general.branch', 1) }}</label>
                                                    <div class="form-control-plaintext">@{{ account.branch_name }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans_choice('core::general.balance', 1) }}</label>
                                                    <div class="form-control-plaintext">
                                                        <span class="badge badge-success badge-lg">@{{ account.currency_symbol }} @{{ account.balance }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Form -->
                        <div class="card card-bordered card-preview mt-4">
                            <div class="card-inner">
                                <h5 class="card-title">{{ trans_choice('teller::general.process_transaction', 1) }}</h5>
                                <form method="post" action="{{url('teller/transaction')}}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="savings_id" v-model="account.savings_id">
                                    
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="transaction_type">{{ trans_choice('core::general.transaction', 1) }} {{ trans_choice('core::general.type', 1) }} <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select @error('transaction_type') is-invalid @enderror" 
                                                            name="transaction_type" id="transaction_type" required v-model="transaction_type">
                                                        <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                                        <option value="deposit">{{ trans_choice('savings::general.deposit', 1) }}</option>
                                                        <option value="withdrawal">{{ trans_choice('savings::general.withdrawal', 1) }}</option>
                                                    </select>
                                                    @error('transaction_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="amount">{{ trans_choice('core::general.amount', 1) }} <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input type="number" step="any" class="form-control @error('amount') is-invalid @enderror" 
                                                           name="amount" id="amount" required v-model="amount">
                                                    @error('amount')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <small v-if="transaction_type === 'withdrawal' && amount > account.raw_balance" class="text-danger">
                                                        Warning: Amount exceeds available balance
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="date">{{ trans_choice('core::general.date', 1) }} <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                           name="date" id="date" value="{{date('Y-m-d')}}" required>
                                                    @error('date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="payment_type_id">{{ trans_choice('core::general.payment', 1) }} {{ trans_choice('core::general.type', 1) }} <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <v-select label="name" :options="payment_types"
                                                              :reduce="payment_type => payment_type.id"
                                                              v-model="payment_type_id">
                                                        <template #search="{attributes, events}">
                                                            <input
                                                                    autocomplete="off"
                                                                    class="vs__search @error('payment_type_id') is-invalid @enderror"
                                                                    v-bind="attributes"
                                                                    v-bind:required="!payment_type_id"
                                                                    v-on="events"
                                                            />
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
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="receipt">{{ trans_choice('core::general.receipt', 1) }} #</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="receipt" id="receipt">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="cheque_number">{{ trans_choice('core::general.cheque', 1) }} #</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="cheque_number" id="cheque_number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="payment_account_number">{{ trans_choice('core::general.account', 1) }} #</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="payment_account_number" id="payment_account_number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="bank_name">{{ trans_choice('core::general.bank', 1) }} {{ trans_choice('core::general.name', 1) }}</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="bank_name" id="bank_name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="routing_code">{{ trans_choice('core::general.routing_code', 1) }}</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="routing_code" id="routing_code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <em class="icon ni ni-check"></em> {{ trans_choice('core::general.submit', 1) }}
                                        </button>
                                        <button type="button" class="btn btn-secondary" @click="clearForm">
                                            <em class="icon ni ni-cross"></em> {{ trans_choice('core::general.clear', 1) }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                payment_types: {!! json_encode($payment_types) !!}
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
                }
            }
        });
    </script>
@endsection
