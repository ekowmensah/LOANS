@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.edit',1) }} {{ trans_choice('client::general.group',1) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('core::general.edit',1) }} {{ trans_choice('client::general.group',1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('client/group')}}">{{ trans_choice('client::general.group',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.edit',1) }} {{ trans_choice('client::general.group',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <form method="post" action="{{ url('client/group/'.$group->id.'/update') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="card card-bordered card-preview">
                <div class="card-header">
                    <h6 class="card-title">{{ trans_choice('client::general.group',1) }} {{ trans_choice('core::general.details',1) }}</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="control-label">{{trans_choice('core::general.name',1)}}</label>
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
                                <label for="description" class="control-label">{{trans_choice('core::general.description',1)}}</label>
                                <textarea name="description" v-model="description" id="description"
                                          class="form-control @error('description') is-invalid @enderror" rows="3"></textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_id" class="control-label">{{trans_choice('core::general.branch',1)}}</label>
                                <select class="form-control @error('branch_id') is-invalid @enderror" name="branch_id" id="branch_id" v-model="branch_id" required>
                                    <option value="">{{trans_choice('core::general.select',1)}}</option>
                                    @foreach($branches as $key)
                                        <option value="{{$key->id}}">{{$key->name}}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loan_officer_id" class="control-label">{{trans_choice('loan::general.loan_officer',1)}}</label>
                                <select class="form-control @error('loan_officer_id') is-invalid @enderror" name="loan_officer_id" id="loan_officer_id" v-model="loan_officer_id" required>
                                    <option value="">{{trans_choice('core::general.select',1)}}</option>
                                    @foreach($loan_officers as $key)
                                        <option value="{{$key->id}}">{{$key->first_name}} {{$key->last_name}}</option>
                                    @endforeach
                                </select>
                                @error('loan_officer_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_frequency" class="control-label">Meeting Frequency</label>
                                <select class="form-control @error('meeting_frequency') is-invalid @enderror" name="meeting_frequency" id="meeting_frequency" v-model="meeting_frequency" required>
                                    <option value="">{{trans_choice('core::general.select',1)}}</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                                @error('meeting_frequency')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_day" class="control-label">Meeting Day</label>
                                <input type="text" name="meeting_day" v-model="meeting_day"
                                       id="meeting_day"
                                       class="form-control @error('meeting_day') is-invalid @enderror"
                                       placeholder="e.g., Monday, 1st Tuesday, etc.">
                                @error('meeting_day')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="control-label">{{trans_choice('core::general.status',1)}}</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" v-model="status" required>
                                    <option value="">{{trans_choice('core::general.select',1)}}</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="closed">Closed</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top ">
                    <button type="submit"
                            class="btn btn-primary  float-right">{{trans_choice('core::general.save',1)}}</button>
                    <a class="btn btn-light float-right mr-2" href="{{ url('client/group') }}">{{ trans_choice('core::general.cancel',1) }}</a>
                </div>
            </div><!-- .card-preview -->
        </form>
    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                name: "{{old('name', $group->name)}}",
                description: "{{old('description', $group->description)}}",
                branch_id: "{{old('branch_id', $group->branch_id)}}",
                loan_officer_id: "{{old('loan_officer_id', $group->loan_officer_id)}}",
                meeting_frequency: "{{old('meeting_frequency', $group->meeting_frequency)}}",
                meeting_day: "{{old('meeting_day', $group->meeting_day)}}",
                status: "{{old('status', $group->status)}}"
            }
        })
    </script>
@endsection
