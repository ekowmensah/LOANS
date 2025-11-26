@extends('core::layouts.master')
@section('title')
    {{ trans_choice('teller::general.teller', 1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('teller::general.teller', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('teller::general.teller',1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content" id="app">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans_choice('teller::general.search_account', 1) }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_number">{{ trans_choice('savings::general.account_number', 1) }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="account_number" v-model="account_number" 
                                       placeholder="Enter account number" @keyup.enter="searchAccount">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" @click="searchAccount" :disabled="searching">
                                        <i class="fas fa-search"></i> {{ trans_choice('core::general.search', 1) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Details Section -->
                <div v-if="account" class="mt-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans_choice('savings::general.account', 1) }} {{ trans_choice('core::general.details', 2) }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img v-if="account.client_photo" :src="'/uploads/' + account.client_photo" 
                                         class="img-circle img-fluid" style="max-width: 100px;" alt="Client Photo">
                                    <i v-else class="fas fa-user-circle fa-5x text-muted"></i>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="200">{{ trans_choice('client::general.client', 1) }}:</th>
                                            <td><strong>@{{ account.client_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('client::general.mobile', 1) }}:</th>
                                            <td>@{{ account.client_mobile }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('savings::general.account_number', 1) }}:</th>
                                            <td>@{{ account.account_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('savings::general.product', 1) }}:</th>
                                            <td>@{{ account.product_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('branch::general.branch', 1) }}:</th>
                                            <td>@{{ account.branch_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('core::general.balance', 1) }}:</th>
                                            <td><h4><span class="badge badge-success">@{{ account.currency_symbol }} @{{ account.balance }}</span></h4></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Form -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans_choice('teller::general.process_transaction', 1) }}</h3>
                        </div>
                        <form method="post" action="{{url('teller/transaction')}}">
                            @csrf
                            <input type="hidden" name="savings_id" v-model="account.savings_id">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transaction_type">{{ trans_choice('core::general.transaction', 1) }} {{ trans_choice('core::general.type', 1) }} <span class="text-danger">*</span></label>
                                            <select class="form-control @error('transaction_type') is-invalid @enderror" 
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">{{ trans_choice('core::general.amount', 1) }} <span class="text-danger">*</span></label>
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">{{ trans_choice('core::general.date', 1) }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                   name="date" id="date" value="{{date('Y-m-d')}}" required>
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_type_id">{{ trans_choice('core::general.payment', 1) }} {{ trans_choice('core::general.type', 1) }} <span class="text-danger">*</span></label>
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="receipt">{{ trans_choice('core::general.receipt', 1) }} #</label>
                                            <input type="text" class="form-control" name="receipt" id="receipt">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cheque_number">{{ trans_choice('core::general.cheque', 1) }} #</label>
                                            <input type="text" class="form-control" name="cheque_number" id="cheque_number">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_account_number">{{ trans_choice('core::general.account', 1) }} #</label>
                                            <input type="text" class="form-control" name="payment_account_number" id="payment_account_number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bank_name">{{ trans_choice('core::general.bank', 1) }} {{ trans_choice('core::general.name', 1) }}</label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="routing_code">{{ trans_choice('core::general.routing_code', 1) }}</label>
                                            <input type="text" class="form-control" name="routing_code" id="routing_code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> {{ trans_choice('core::general.submit', 1) }}
                                </button>
                                <button type="button" class="btn btn-secondary" @click="clearForm">
                                    <i class="fas fa-times"></i> {{ trans_choice('core::general.clear', 1) }}
                                </button>
                            </div>
                        </form>
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
