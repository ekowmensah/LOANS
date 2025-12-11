@extends('core::layouts.master')

@section('title')
    Edit Client Assignment
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Client Assignment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/dashboard') }}">Field Agent</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('field_agent.assignments.index') }}">Client Assignments</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Client Assignment</h3>
                <div class="card-tools">
                    <a href="{{ route('field_agent.assignments.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back', 1) }}
                    </a>
                </div>
            </div>
            <form method="post" action="{{ route('field_agent.assignments.update', $assignment->id) }}">
                @csrf
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Changing the field agent or client will update the assignment. This may affect ongoing collections.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field_agent_id" class="control-label">Field Agent <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="field_agent_id" id="field_agent_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($fieldAgents as $agent)
                                        <option value="{{ $agent->id }}" {{ (old('field_agent_id', $assignment->field_agent_id) == $agent->id) ? 'selected' : '' }}>
                                            {{ $agent->full_name }} ({{ $agent->agent_code }}) - {{ $agent->branch ? $agent->branch->name : 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('field_agent_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id" class="control-label">Client <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="client_id" id="client_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ (old('client_id', $assignment->client_id) == $client->id) ? 'selected' : '' }}>
                                            #{{ $client->id }} - {{ $client->first_name }} {{ $client->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_date" class="control-label">Assignment Date <span class="text-danger">*</span></label>
                                <input type="date" name="assigned_date" id="assigned_date" class="form-control" value="{{ old('assigned_date', $assignment->assigned_date->format('Y-m-d')) }}" required>
                                @error('assigned_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Current Status</label>
                                <div class="form-control-plaintext">
                                    @if($assignment->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                    @if($assignment->unassigned_date)
                                        <small class="text-muted">(Unassigned on {{ $assignment->unassigned_date->format('Y-m-d') }})</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{ trans_choice('core::general.notes', 1) }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes about this assignment">{{ old('notes', $assignment->notes) }}</textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h4 class="card-title">Assignment History</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="30%">Created:</th>
                                            <td>{{ $assignment->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $assignment->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Assigned By:</th>
                                            <td>{{ $assignment->assignedBy ? $assignment->assignedBy->first_name . ' ' . $assignment->assignedBy->last_name : 'System' }}</td>
                                        </tr>
                                        @if($assignment->unassigned_date)
                                        <tr>
                                            <th>Unassigned Date:</th>
                                            <td>{{ $assignment->unassigned_date->format('Y-m-d') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ trans_choice('core::general.save', 1) }} Changes
                    </button>
                    <a href="{{ route('field_agent.assignments.index') }}" class="btn btn-default">
                        {{ trans_choice('core::general.cancel', 1) }}
                    </a>
                    @if($assignment->status === 'active')
                        <a href="{{ route('field_agent.assignments.deactivate', $assignment->id) }}" class="btn btn-warning float-right confirm-action" data-message="Are you sure you want to deactivate this assignment?">
                            <i class="fas fa-ban"></i> Deactivate Assignment
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Confirm action handler
            $('.confirm-action').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var message = $(this).data('message') || 'Are you sure you want to perform this action?';
                
                if (confirm(message)) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection
