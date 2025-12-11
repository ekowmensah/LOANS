@extends('core::layouts.master')

@section('title')
    Assign Client to Field Agent
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Assign Client to Field Agent</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/dashboard') }}">Field Agent</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('field_agent.assignments.index') }}">Client Assignments</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.add', 1) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assign Client to Field Agent</h3>
                <div class="card-tools">
                    <a href="{{ route('field_agent.assignments.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back', 1) }}
                    </a>
                </div>
            </div>
            <form method="post" action="{{ route('field_agent.assignments.store') }}">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> If the client is already assigned to another field agent, the previous assignment will be automatically deactivated.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field_agent_id" class="control-label">Field Agent <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="field_agent_id" id="field_agent_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($fieldAgents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('field_agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->full_name }} ({{ $agent->agent_code }}) - {{ $agent->branch ? $agent->branch->name : 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('field_agent_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Select the field agent to assign the client to</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id" class="control-label">Client <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="client_id" id="client_id" required>
                                    <option value="">{{ trans_choice('core::general.select', 1) }}</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            #{{ $client->id }} - {{ $client->first_name }} {{ $client->last_name }}
                                            @if($client->activeFieldAgentAssignment)
                                                <span style="color: orange;">(Currently assigned to {{ $client->activeFieldAgentAssignment->fieldAgent->full_name }})</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Select the client to assign</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_date" class="control-label">Assignment Date <span class="text-danger">*</span></label>
                                <input type="date" name="assigned_date" id="assigned_date" class="form-control" value="{{ old('assigned_date', date('Y-m-d')) }}" required>
                                @error('assigned_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Date when the assignment becomes effective</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{ trans_choice('core::general.notes', 1) }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes about this assignment">{{ old('notes') }}</textarea>
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
                                    <h4 class="card-title">Bulk Assignment</h4>
                                </div>
                                <div class="card-body">
                                    <p>To assign multiple clients to a field agent at once, use the single field agent selection above and select multiple clients below:</p>
                                    <div class="form-group">
                                        <label>Select Multiple Clients (Optional)</label>
                                        <select class="form-control select2" name="client_ids[]" id="client_ids" multiple>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">
                                                    #{{ $client->id }} - {{ $client->first_name }} {{ $client->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple clients. If you select clients here, the single client selection above will be ignored.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ trans_choice('core::general.save', 1) }} Assignment
                    </button>
                    <a href="{{ route('field_agent.assignments.index') }}" class="btn btn-default">
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
                theme: 'bootstrap4',
                width: '100%'
            });

            // Show client assignment status
            $('#client_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var clientId = $(this).val();
                
                if (clientId) {
                    // You can add AJAX call here to fetch current assignment details
                    console.log('Selected client:', clientId);
                }
            });

            // Handle bulk vs single selection
            $('#client_ids').on('change', function() {
                if ($(this).val() && $(this).val().length > 0) {
                    $('#client_id').prop('disabled', true);
                } else {
                    $('#client_id').prop('disabled', false);
                }
            });

            $('#client_id').on('change', function() {
                if ($(this).val()) {
                    $('#client_ids').prop('disabled', true);
                } else {
                    $('#client_ids').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
