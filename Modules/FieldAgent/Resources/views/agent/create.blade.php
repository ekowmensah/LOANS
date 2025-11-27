@extends('core::layouts.master')

@section('title')
    {{ trans_choice('core::general.add', 1) }} {{ trans_choice('fieldagent::general.field_agent', 1) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('core::general.add', 1) }} {{ trans_choice('fieldagent::general.field_agent', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/agent') }}">{{ trans_choice('fieldagent::general.field_agent', 2) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add', 1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans_choice('core::general.add', 1) }} {{ trans_choice('fieldagent::general.field_agent', 1) }}</h3>
                <div class="card-tools">
                    <a href="{{ url('field-agent/agent') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back', 1) }}
                    </a>
                </div>
            </div>
            <form method="post" action="{{ url('field-agent/agent/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id" class="control-label">{{ trans_choice('core::general.user', 1) }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="user_id" id="user_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agent_code" class="control-label">{{ trans_choice('fieldagent::general.agent_code', 1) }} <span class="text-danger">*</span></label>
                                <input type="text" name="agent_code" id="agent_code" class="form-control" value="{{ old('agent_code') }}" required>
                                @error('agent_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_id" class="control-label">{{ trans_choice('core::general.branch', 1) }} <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="branch_id" id="branch_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number" class="control-label">{{ trans_choice('core::general.phone', 1) }}</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="commission_rate" class="control-label">{{ trans_choice('fieldagent::general.commission_rate', 1) }} (%)</label>
                                <input type="number" name="commission_rate" id="commission_rate" class="form-control" value="{{ old('commission_rate', 0) }}" min="0" max="100" step="0.01">
                                @error('commission_rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_amount" class="control-label">{{ trans_choice('fieldagent::general.target', 1) }} (Monthly)</label>
                                <input type="number" name="target_amount" id="target_amount" class="form-control" value="{{ old('target_amount', 0) }}" min="0" step="0.01">
                                @error('target_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="national_id" class="control-label">{{ trans_choice('core::general.id_number', 1) }}</label>
                                <input type="text" name="national_id" id="national_id" class="form-control" value="{{ old('national_id') }}">
                                @error('national_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo" class="control-label">{{ trans_choice('core::general.photo', 1) }}</label>
                                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*">
                                <small class="form-text text-muted">Max size: 2MB</small>
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{ trans_choice('core::general.notes', 1) }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ trans_choice('core::general.save', 1) }}
                    </button>
                    <a href="{{ url('field-agent/agent') }}" class="btn btn-default">
                        {{ trans_choice('core::general.cancel', 1) }}
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
