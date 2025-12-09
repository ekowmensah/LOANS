@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.charge',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.charge',1) }}
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
                                    href="{{url('loan/charge')}}">{{ trans_choice('loan::general.charge',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.charge',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <form method="post" action="{{ url('loan/charge/store') }}">
            {{csrf_field()}}
            <div class="card card-bordered card-preview">
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name"
                                       class="control-label">{{trans_choice('core::general.name',1)}}</label>
                                <input type="text" name="name" v-model="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="loan_charge_type_id"
                                       class="control-label">{{trans_choice('loan::general.charge',1)}} {{trans_choice('core::general.type',1)}}
                                </label>
                                <v-select label="name" :options="charge_types"
                                          :reduce="charge_type => charge_type.id"
                                          v-model="loan_charge_type_id">
                                    <template #search="{attributes, events}">
                                        <input
                                                autocomplete="off"
                                                class="vs__search @error('loan_charge_type_id') is-invalid @enderror"
                                                :required="!loan_charge_type_id"
                                                v-bind="attributes"
                                                v-on="events"
                                        />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_charge_type_id"
                                       v-model="loan_charge_type_id">
                                @error('loan_charge_type_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="amount"
                                       class="control-label">{{trans_choice('core::general.amount',1)}}</label>
                                <input type="text" name="amount" v-model="amount"
                                       id="amount"
                                       class="form-control numeric @error('amount') is-invalid @enderror" required>
                                @error('amount')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="loan_charge_option_id"
                                       class="control-label">{{trans_choice('loan::general.charge',1)}} {{trans_choice('core::general.option',1)}}
                                </label>
                                <v-select label="name" :options="charge_options"
                                          :reduce="charge_option => charge_option.id"
                                          v-model="loan_charge_option_id">
                                    <template #search="{attributes, events}">
                                        <input
                                                autocomplete="off"
                                                class="vs__search @error('loan_charge_type_id') is-invalid @enderror"
                                                :required="!loan_charge_option_id"
                                                v-bind="attributes"
                                                v-on="events"
                                        />
                                    </template>
                                </v-select>
                                <input type="hidden" name="loan_charge_option_id"
                                       v-model="loan_charge_option_id">
                                @error('loan_charge_option_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="currency_id"
                                       class="control-label">{{trans_choice('core::general.currency',1)}}
                                </label>
                                <v-select label="name" :options="currencies"
                                          :reduce="currency => currency.id"
                                          v-model="currency_id">
                                    <template #search="{attributes, events}">
                                        <input
                                                autocomplete="off"
                                                class="vs__search @error('currency_id') is-invalid @enderror"
                                                :required="!currency_id"
                                                v-bind="attributes"
                                                v-on="events"
                                        />
                                    </template>
                                </v-select>
                                <input type="hidden" name="currency_id"
                                       v-model="currency_id">
                                @error('currency_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="is_penalty" class="control-label">{{trans_choice('loan::general.penalty',1)}}</label>
                                <select v-model="is_penalty" class="form-control @error('is_penalty') is-invalid @enderror" name="is_penalty" id="is_penalty" required>
                                    <option value="0" selected>{{trans_choice("core::general.no",1)}}</option>
                                    <option value="1">{{trans_choice("core::general.yes",1)}}</option>
                                </select>
                                @error('is_penalty')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="allow_override" class="control-label">{{trans('loan::general.override')}}</label>
                                <select v-model="allow_override" class="form-control @error('allow_override') is-invalid @enderror" name="allow_override" id="allow_override" required>
                                    <option value="0" selected>{{trans_choice("core::general.no",1)}}</option>
                                    <option value="1">{{trans_choice("core::general.yes",1)}}</option>
                                </select>
                                @error('allow_override')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="active" class="control-label">{{trans('core::general.active')}}</label>
                                <select v-model="active" class="form-control @error('active') is-invalid @enderror" name="active" id="active" required>
                                    <option value="0" selected>{{trans_choice("core::general.no",1)}}</option>
                                    <option value="1">{{trans_choice("core::general.yes",1)}}</option>
                                </select>
                                @error('active')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Frequency Settings for Overdue on Loan Maturity (Type 7) -->
                        <div class="col-md-12" v-if="loan_charge_type_id == 7">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="mb-0">Recurring Penalty Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="schedule" class="control-label">Enable Recurring Penalty</label>
                                        <select v-model="schedule" class="form-control" name="schedule" id="schedule">
                                            <option value="0">No - Apply Once Only</option>
                                            <option value="1">Yes - Apply Repeatedly</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            If enabled, this penalty will be applied repeatedly based on the frequency below
                                        </small>
                                    </div>
                                    
                                    <div v-if="schedule == 1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="schedule_frequency" class="control-label">Frequency</label>
                                                    <input type="number" name="schedule_frequency" v-model="schedule_frequency"
                                                           id="schedule_frequency" min="1"
                                                           class="form-control @error('schedule_frequency') is-invalid @enderror"
                                                           :required="schedule == 1">
                                                    @error('schedule_frequency')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="schedule_frequency_type" class="control-label">Frequency Type</label>
                                                    <select v-model="schedule_frequency_type" class="form-control @error('schedule_frequency_type') is-invalid @enderror" 
                                                            name="schedule_frequency_type" id="schedule_frequency_type" :required="schedule == 1">
                                                        <option value="days">Days</option>
                                                        <option value="weeks">Weeks</option>
                                                        <option value="months">Months</option>
                                                        <option value="years">Years</option>
                                                    </select>
                                                    @error('schedule_frequency_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info" v-if="schedule_frequency && schedule_frequency_type">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Example:</strong> Penalty will be applied every <strong>@{{ schedule_frequency }} @{{ schedule_frequency_type }}</strong> 
                                            while the loan remains overdue past maturity date.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top ">
                    <button type="submit"
                            class="btn btn-primary  float-right">{{trans_choice('core::general.save',1)}}</button>
                </div>
            </div><!-- .card-preview -->
        </form>
    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                name: "{{old('name')}}",
                currency_id: parseInt("{{old('currency_id')}}"),
                loan_charge_option_id: parseInt("{{old('loan_charge_option_id')}}"),
                loan_charge_type_id: parseInt("{{old('loan_charge_type_id')}}"),
                amount: "{{old('amount')}}",
                active: "{{old('active',1)}}",
                is_penalty: "{{old('is_penalty',0)}}",
                allow_override: "{{old('allow_override',0)}}",
                schedule: "{{old('schedule',0)}}",
                schedule_frequency: "{{old('schedule_frequency','')}}",
                schedule_frequency_type: "{{old('schedule_frequency_type','days')}}",
                charge_types: {!! json_encode($charge_types) !!},
                charge_options: {!! json_encode($charge_options) !!},
                currencies: {!! json_encode($currencies) !!},


            }
        })
    </script>
@endsection