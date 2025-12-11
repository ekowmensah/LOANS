@extends('core::layouts.master')

@section('title')
    Client Assignments
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Client Assignments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/dashboard') }}">Field Agent</a></li>
                        <li class="breadcrumb-item active">Client Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Client Assignments</h3>
                <div class="card-tools">
                    @can('field_agent.assignments.create')
                        <a href="{{ route('field_agent.assignments.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> {{ trans_choice('core::general.add', 1) }} Assignment
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Field Agent</label>
                            <select class="form-control select2" id="agent_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                @foreach(\Modules\FieldAgent\Entities\FieldAgent::with('user')->where('status', 'active')->get() as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.status', 1) }}</label>
                            <select class="form-control" id="status_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" class="form-control" id="date_from_filter">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" class="form-control" id="date_to_filter">
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="assignments-table">
                    <thead>
                        <tr>
                            <th>Agent Code</th>
                            <th>Field Agent</th>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Assigned Date</th>
                            <th>Assigned By</th>
                            <th>{{ trans_choice('core::general.status', 1) }}</th>
                            <th>{{ trans_choice('core::general.action', 1) }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#assignments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/assignments/data') }}",
                    data: function(d) {
                        d.field_agent_id = $('#agent_filter').val();
                        d.status = $('#status_filter').val();
                        d.date_from = $('#date_from_filter').val();
                        d.date_to = $('#date_to_filter').val();
                    }
                },
                columns: [
                    {data: 'agent_code', name: 'agent_code'},
                    {data: 'field_agent_name', name: 'field_agent_name'},
                    {data: 'client_id_number', name: 'client_id_number'},
                    {data: 'client_name', name: 'client_name'},
                    {data: 'assigned_date', name: 'assigned_date'},
                    {data: 'assigned_by_name', name: 'assigned_by_name'},
                    {data: 'status_badge', name: 'status', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[4, 'desc']]
            });

            $('#agent_filter, #status_filter, #date_from_filter, #date_to_filter').change(function() {
                table.draw();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Confirm action handler
            $(document).on('click', '.confirm-action', function(e) {
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
