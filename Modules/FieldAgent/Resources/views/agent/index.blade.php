@extends('core::layouts.master')

@section('title')
    {{ trans_choice('fieldagent::general.field_agent', 2) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('fieldagent::general.field_agent', 2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item active">{{ trans_choice('fieldagent::general.field_agent', 2) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans_choice('fieldagent::general.field_agent', 2) }}</h3>
                <div class="card-tools">
                    @can('field_agent.agents.create')
                        <a href="{{ url('field-agent/agent/create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> {{ trans_choice('core::general.add', 1) }}
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.branch', 1) }}</label>
                            <select class="form-control select2" id="branch_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                @foreach(\Modules\Branch\Entities\Branch::all() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ trans_choice('core::general.status', 1) }}</label>
                            <select class="form-control" id="status_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="field-agents-table">
                    <thead>
                        <tr>
                            <th>{{ trans_choice('fieldagent::general.agent_code', 1) }}</th>
                            <th>{{ trans_choice('core::general.name', 1) }}</th>
                            <th>{{ trans_choice('core::general.branch', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.commission_rate', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.target', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.performance', 1) }}</th>
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
            var table = $('#field-agents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/agent/data') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_filter').val();
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [
                    {data: 'agent_code', name: 'agent_code'},
                    {data: 'user', name: 'user'},
                    {data: 'branch', name: 'branch'},
                    {data: 'commission_rate', name: 'commission_rate'},
                    {data: 'target_amount', name: 'target_amount'},
                    {data: 'performance', name: 'performance', orderable: false},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']]
            });

            $('#branch_filter, #status_filter').change(function() {
                table.draw();
            });

            $('.select2').select2();
        });
    </script>
@endsection
