@extends('core::layouts.master')

@section('title')
    Assign Clients to Field Agents
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Assign Clients to Field Agents</h1>
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
                    <button type="button" class="btn btn-sm btn-success" id="bulk-assign-btn">
                        <i class="fas fa-users"></i> Bulk Assign
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter by Field Agent</label>
                            <select class="form-control select2" id="agent_filter">
                                <option value="">All Clients</option>
                                <option value="unassigned">Unassigned Clients</option>
                                @foreach($fieldAgents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>How it works:</strong> Each client can be assigned to one field agent. The assigned agent can collect payments from that client.
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="clients-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Mobile</th>
                            <th>Branch</th>
                            <th>Assigned Field Agent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Assign Agent Modal -->
    <div class="modal fade" id="assignAgentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Field Agent</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Client: <strong id="modal-client-name"></strong></p>
                    <div class="form-group">
                        <label>Select Field Agent</label>
                        <select class="form-control select2" id="modal-field-agent-id" style="width: 100%;">
                            <option value="">-- Select Field Agent --</option>
                            @foreach($fieldAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-assignment-btn">Save Assignment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Assign Modal -->
    <div class="modal fade" id="bulkAssignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Assign Clients</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Selected Clients: <strong id="bulk-selected-count">0</strong></p>
                    <div class="form-group">
                        <label>Assign to Field Agent</label>
                        <select class="form-control select2" id="bulk-field-agent-id" style="width: 100%;">
                            <option value="">-- Select Field Agent --</option>
                            @foreach($fieldAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-bulk-assignment-btn">Assign Selected</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedClientId = null;
            let selectedClients = [];

            // Initialize DataTable
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/client-assignments/data') }}",
                    data: function(d) {
                        d.field_agent_id = $('#agent_filter').val();
                    }
                },
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return '<input type="checkbox" class="client-checkbox" value="' + data + '">';
                        }
                    },
                    {data: 'client_id_number', name: 'id'},
                    {data: 'client_name', name: 'first_name'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'field_agent', name: 'field_agent', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, 'asc']]
            });

            // Filter change
            $('#agent_filter').change(function() {
                table.draw();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Select all checkbox
            $('#select-all').on('click', function() {
                $('.client-checkbox').prop('checked', this.checked);
                updateSelectedClients();
            });

            // Individual checkbox
            $(document).on('change', '.client-checkbox', function() {
                updateSelectedClients();
            });

            function updateSelectedClients() {
                selectedClients = [];
                $('.client-checkbox:checked').each(function() {
                    selectedClients.push($(this).val());
                });
                $('#bulk-selected-count').text(selectedClients.length);
            }

            // Assign agent button
            $(document).on('click', '.assign-agent-btn', function() {
                selectedClientId = $(this).data('client-id');
                const clientName = $(this).data('client-name');
                const currentAgent = $(this).data('current-agent');

                $('#modal-client-name').text(clientName);
                $('#modal-field-agent-id').val(currentAgent).trigger('change');
                $('#assignAgentModal').modal('show');
            });

            // Save assignment
            $('#save-assignment-btn').on('click', function() {
                const fieldAgentId = $('#modal-field-agent-id').val();
                const $btn = $(this);

                if (!fieldAgentId) {
                    alert('Please select a field agent');
                    return;
                }

                // Show loading state
                $btn.prop('disabled', true);
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: "{{ url('field-agent/client-assignments/update') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        client_id: selectedClientId,
                        field_agent_id: fieldAgentId
                    },
                    success: function(response) {
                        $('#assignAgentModal').modal('hide');
                        table.draw();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    },
                    complete: function() {
                        // Reset button state
                        $btn.prop('disabled', false);
                        $btn.html('Save Assignment');
                    }
                });
            });

            // Unassign agent button
            $(document).on('click', '.unassign-agent-btn', function() {
                if (!confirm('Are you sure you want to remove the field agent from this client?')) {
                    return;
                }

                const clientId = $(this).data('client-id');
                const $btn = $(this);
                const originalHtml = $btn.html();

                // Show loading state
                $btn.prop('disabled', true);
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Removing...');

                $.ajax({
                    url: "{{ url('field-agent/client-assignments/update') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        client_id: clientId,
                        field_agent_id: null
                    },
                    success: function(response) {
                        table.draw();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                        $btn.prop('disabled', false);
                        $btn.html(originalHtml);
                    }
                });
            });

            // Bulk assign button
            $('#bulk-assign-btn').on('click', function() {
                if (selectedClients.length === 0) {
                    alert('Please select at least one client');
                    return;
                }
                $('#bulkAssignModal').modal('show');
            });

            // Save bulk assignment
            $('#save-bulk-assignment-btn').on('click', function() {
                const fieldAgentId = $('#bulk-field-agent-id').val();
                const $btn = $(this);

                if (!fieldAgentId) {
                    alert('Please select a field agent');
                    return;
                }

                // Show loading state
                $btn.prop('disabled', true);
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Assigning...');

                $.ajax({
                    url: "{{ url('field-agent/client-assignments/bulk-assign') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_agent_id: fieldAgentId,
                        client_ids: selectedClients
                    },
                    success: function(response) {
                        $('#bulkAssignModal').modal('hide');
                        $('#select-all').prop('checked', false);
                        selectedClients = [];
                        table.draw();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    },
                    complete: function() {
                        // Reset button state
                        $btn.prop('disabled', false);
                        $btn.html('Assign Selected');
                    }
                });
            });
        });
    </script>
@endsection
