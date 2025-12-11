@extends('core::layouts.master')

@section('title')
    Assign Clients to Field Agents
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
    <style>
        .filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .filter-card .form-control,
        .filter-card .select2-container--bootstrap4 .select2-selection {
            background: rgba(255, 255, 255, 0.9);
            border: none;
        }
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .stats-card h4 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            color: #667eea;
        }
        .stats-card p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }
        .bulk-actions-bar {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: none;
        }
        .bulk-actions-bar.active {
            display: block;
        }
    </style>
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
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <h4 id="total-clients">0</h4>
                    <p>Total Clients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h4 id="assigned-clients" style="color: #28a745;">0</h4>
                    <p>Assigned Clients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h4 id="unassigned-clients" style="color: #ffc107;">0</h4>
                    <p>Unassigned Clients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h4 id="selected-count" style="color: #667eea;">0</h4>
                    <p>Selected for Bulk Action</p>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="filter-card">
            <h5 class="mb-3"><i class="fas fa-filter"></i> Search & Filter Clients</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search Client</label>
                        <input type="text" class="form-control" id="search_client" placeholder="Name, ID, or Mobile">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter by Field Agent</label>
                        <select class="form-control select2" id="agent_filter">
                            <option value="">All Clients</option>
                            <option value="unassigned">Unassigned Only</option>
                            <option value="assigned">Assigned Only</option>
                            @foreach($fieldAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->full_name }} ({{ $agent->agent_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter by Branch</label>
                        <select class="form-control select2" id="branch_filter">
                            <option value="">All Branches</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-light btn-block" id="reset-filters">
                            <i class="fas fa-redo"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions-bar" id="bulk-actions-bar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <strong><span id="bulk-selected-count-text">0</span> client(s) selected</strong>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success" id="bulk-assign-btn">
                        <i class="fas fa-user-plus"></i> Assign to Field Agent
                    </button>
                    <button type="button" class="btn btn-danger" id="bulk-unassign-btn">
                        <i class="fas fa-user-times"></i> Remove Field Agent
                    </button>
                    <button type="button" class="btn btn-secondary" id="clear-selection-btn">
                        <i class="fas fa-times"></i> Clear Selection
                    </button>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Client Assignments</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" id="select-all-pages">
                        <i class="fas fa-check-double"></i> Select All on Page
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover" id="clients-table">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="select-all"></th>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Mobile</th>
                            <th>Branch</th>
                            <th>Assigned Field Agent</th>
                            <th width="150">Action</th>
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
            let stats = {total: 0, assigned: 0, unassigned: 0};

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Initialize DataTable
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/client-assignments/data') }}",
                    data: function(d) {
                        d.field_agent_id = $('#agent_filter').val();
                        d.branch_id = $('#branch_filter').val();
                        d.search_query = $('#search_client').val();
                    },
                    dataSrc: function(json) {
                        // Update statistics
                        updateStats();
                        return json.data;
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
                order: [[1, 'asc']],
                pageLength: 25,
                drawCallback: function() {
                    // Restore checkbox states
                    $('.client-checkbox').each(function() {
                        if (selectedClients.includes($(this).val())) {
                            $(this).prop('checked', true);
                        }
                    });
                }
            });

            // Load branches for filter
            $.get("{{ url('branch/get_branches') }}", function(data) {
                if (data && data.length > 0) {
                    data.forEach(function(branch) {
                        $('#branch_filter').append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    });
                }
            });

            // Update statistics
            function updateStats() {
                $.get("{{ url('field-agent/client-assignments/data') }}", {length: -1}, function(response) {
                    stats.total = response.recordsTotal || 0;
                    stats.assigned = 0;
                    stats.unassigned = 0;
                    
                    if (response.data) {
                        response.data.forEach(function(client) {
                            if (client.field_agent_id) {
                                stats.assigned++;
                            } else {
                                stats.unassigned++;
                            }
                        });
                    }
                    
                    $('#total-clients').text(stats.total);
                    $('#assigned-clients').text(stats.assigned);
                    $('#unassigned-clients').text(stats.unassigned);
                });
            }

            // Filter changes
            $('#agent_filter, #branch_filter').change(function() {
                table.draw();
            });

            // Search with debounce
            let searchTimeout;
            $('#search_client').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    table.draw();
                }, 500);
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search_client').val('');
                $('#agent_filter').val('').trigger('change');
                $('#branch_filter').val('').trigger('change');
                table.draw();
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

            // Select all on current page
            $('#select-all-pages').click(function() {
                $('.client-checkbox').prop('checked', true);
                updateSelectedClients();
            });

            // Clear selection
            $('#clear-selection-btn').click(function() {
                selectedClients = [];
                $('.client-checkbox').prop('checked', false);
                $('#select-all').prop('checked', false);
                updateSelectedClients();
            });

            function updateSelectedClients() {
                selectedClients = [];
                $('.client-checkbox:checked').each(function() {
                    selectedClients.push($(this).val());
                });
                $('#bulk-selected-count').text(selectedClients.length);
                $('#selected-count').text(selectedClients.length);
                $('#bulk-selected-count-text').text(selectedClients.length);
                
                // Show/hide bulk actions bar
                if (selectedClients.length > 0) {
                    $('#bulk-actions-bar').addClass('active');
                } else {
                    $('#bulk-actions-bar').removeClass('active');
                }
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
                        updateSelectedClients();
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

            // Bulk unassign
            $('#bulk-unassign-btn').on('click', function() {
                if (selectedClients.length === 0) {
                    alert('Please select at least one client');
                    return;
                }

                if (!confirm('Are you sure you want to remove field agents from ' + selectedClients.length + ' selected client(s)?')) {
                    return;
                }

                const $btn = $(this);
                const originalHtml = $btn.html();

                // Show loading state
                $btn.prop('disabled', true);
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Removing...');

                $.ajax({
                    url: "{{ url('field-agent/client-assignments/bulk-assign') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_agent_id: null,
                        client_ids: selectedClients
                    },
                    success: function(response) {
                        selectedClients = [];
                        $('#select-all').prop('checked', false);
                        updateSelectedClients();
                        table.draw();
                        alert(selectedClients.length + ' client(s) unassigned successfully.');
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    },
                    complete: function() {
                        // Reset button state
                        $btn.prop('disabled', false);
                        $btn.html(originalHtml);
                    }
                });
            });

            // Initial stats load
            updateStats();
        });
    </script>
@endsection
