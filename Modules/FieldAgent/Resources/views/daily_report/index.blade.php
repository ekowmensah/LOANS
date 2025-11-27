@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.daily_report', 2) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('fieldagent::general.daily_report', 2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('fieldagent::general.daily_report', 2) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans_choice('fieldagent::general.daily_report', 2) }}</h3>
                <div class="card-tools">
                    @can('field_agent.reports.create')
                        <a href="{{ url('field-agent/daily-report/create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Submit Daily Report
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ trans_choice('fieldagent::general.field_agent', 1) }}</label>
                            <select class="form-control select2" id="field_agent_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                @foreach($fieldAgents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->agent_code }} - {{ $agent->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.status', 1) }}</label>
                            <select class="form-control" id="status_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                <option value="pending">Pending</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.start_date', 1) }}</label>
                            <input type="date" class="form-control" id="start_date_filter">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.end_date', 1) }}</label>
                            <input type="date" class="form-control" id="end_date_filter">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-default btn-block" id="clear_filters">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="reports-table">
                    <thead>
                        <tr>
                            <th>{{ trans_choice('fieldagent::general.report_date', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.field_agent', 1) }}</th>
                            <th>Collections</th>
                            <th>Amount Collected</th>
                            <th>Cash Deposited</th>
                            <th>Variance</th>
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
            var table = $('#reports-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/daily-report/data') }}",
                    data: function(d) {
                        d.field_agent_id = $('#field_agent_filter').val();
                        d.status = $('#status_filter').val();
                        d.start_date = $('#start_date_filter').val();
                        d.end_date = $('#end_date_filter').val();
                    }
                },
                columns: [
                    {data: 'report_date', name: 'report_date'},
                    {data: 'field_agent', name: 'field_agent'},
                    {data: 'total_collections', name: 'total_collections'},
                    {data: 'total_amount_collected', name: 'total_amount_collected'},
                    {data: 'cash_deposited', name: 'cash_deposited'},
                    {data: 'variance', name: 'variance'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#field_agent_filter, #status_filter, #start_date_filter, #end_date_filter').change(function() {
                table.draw();
            });

            $('#clear_filters').click(function() {
                $('#field_agent_filter').val('').trigger('change');
                $('#status_filter').val('');
                $('#start_date_filter').val('');
                $('#end_date_filter').val('');
                table.draw();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
