@extends('core::layouts.master')

@section('title')
    Verify {{ trans_choice('fieldagent::general.collection', 2) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Verify {{ trans_choice('fieldagent::general.collection', 2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/collection') }}">{{ trans_choice('fieldagent::general.collection', 2) }}</a></li>
                        <li class="breadcrumb-item active">Verify</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Verification</span>
                        <span class="info-box-number">{{ $pendingCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Collections</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" id="bulk-verify-btn" style="display: none;">
                        <i class="fas fa-check-double"></i> Verify Selected (<span id="selected-count">0</span>)
                    </button>
                    <a href="{{ url('field-agent/collection') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to All Collections
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
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
                            <label>Branch</label>
                            <select class="form-control" id="branch_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ trans_choice('fieldagent::general.collection_type', 1) }}</label>
                            <select class="form-control" id="type_filter">
                                <option value="">{{ trans_choice('core::general.all', 1) }}</option>
                                <option value="savings_deposit">Savings Deposit</option>
                                <option value="loan_repayment">Loan Repayment</option>
                                <option value="share_purchase">Share Purchase</option>
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
                
                <table class="table table-bordered table-hover" id="pending-collections-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>{{ trans_choice('fieldagent::general.receipt_number', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.field_agent', 1) }}</th>
                            <th>{{ trans_choice('client::general.client', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.collection_type', 1) }}</th>
                            <th>{{ trans_choice('core::general.amount', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.collection_date', 1) }}</th>
                            <th>{{ trans_choice('fieldagent::general.location', 1) }}</th>
                            <th>{{ trans_choice('core::general.action', 1) }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="reject-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Collection</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Collection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#pending-collections-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('field-agent/collection/data') }}",
                    data: function(d) {
                        d.status = 'pending';
                        d.field_agent_id = $('#field_agent_filter').val();
                        d.branch_id = $('#branch_filter').val();
                        d.collection_type = $('#type_filter').val();
                        d.start_date = $('#start_date_filter').val();
                        d.end_date = $('#end_date_filter').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return '<input type="checkbox" class="collection-checkbox" value="' + data.id + '">';
                        }
                    },
                    {data: 'receipt_number', name: 'receipt_number'},
                    {data: 'field_agent', name: 'field_agent'},
                    {data: 'client', name: 'client'},
                    {data: 'collection_type', name: 'collection_type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'collection_date', name: 'collection_date'},
                    {
                        data: null,
                        render: function(data) {
                            if (data.latitude && data.longitude) {
                                return '<a href="https://maps.google.com/?q=' + data.latitude + ',' + data.longitude + '" target="_blank"><i class="fas fa-map-marker-alt"></i> View Map</a>';
                            }
                            return 'N/A';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var actions = '<div class="btn-group">';
                            actions += '<a href="{{ url("field-agent/collection") }}/' + data.id + '/show" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';
                            actions += '<form method="post" action="{{ url("field-agent/collection") }}/' + data.id + '/verify" style="display:inline;">';
                            actions += '@csrf';
                            actions += '<button type="submit" class="btn btn-success btn-sm" onclick="return confirm(\'Verify this collection?\')"><i class="fa fa-check"></i> Verify</button>';
                            actions += '</form>';
                            actions += '<button type="button" class="btn btn-danger btn-sm reject-btn" data-id="' + data.id + '"><i class="fa fa-times"></i> Reject</button>';
                            actions += '</div>';
                            return actions;
                        }
                    }
                ],
                order: [[1, 'desc']]
            });

            // Real-time filtering
            $('#field_agent_filter, #branch_filter, #type_filter, #start_date_filter, #end_date_filter').change(function() {
                table.draw();
            });

            // Clear filters
            $('#clear_filters').click(function() {
                $('#field_agent_filter').val('').trigger('change');
                $('#branch_filter').val('');
                $('#type_filter').val('');
                $('#start_date_filter').val('');
                $('#end_date_filter').val('');
                table.draw();
            });

            // Initialize select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Bulk verification functionality
            var selectedCollections = [];

            // Select all checkbox
            $('#select-all').on('click', function() {
                var checked = $(this).prop('checked');
                $('.collection-checkbox:visible').prop('checked', checked);
                updateSelectedCollections();
            });

            // Individual checkbox
            $(document).on('change', '.collection-checkbox', function() {
                updateSelectedCollections();
            });

            // Update selected collections array and button
            function updateSelectedCollections() {
                selectedCollections = [];
                $('.collection-checkbox:checked').each(function() {
                    selectedCollections.push($(this).val());
                });
                
                $('#selected-count').text(selectedCollections.length);
                
                if (selectedCollections.length > 0) {
                    $('#bulk-verify-btn').show();
                } else {
                    $('#bulk-verify-btn').hide();
                }
            }

            // Bulk verify button click
            $('#bulk-verify-btn').on('click', function() {
                if (selectedCollections.length === 0) {
                    alert('Please select at least one collection to verify.');
                    return;
                }

                if (confirm('Are you sure you want to verify ' + selectedCollections.length + ' collection(s)?')) {
                    $.ajax({
                        url: '{{ url("field-agent/collection/bulk-verify") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            collection_ids: selectedCollections
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                table.draw();
                                selectedCollections = [];
                                $('#select-all').prop('checked', false);
                                updateSelectedCollections();
                                // Update pending count
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error verifying collections. Please try again.');
                        }
                    });
                }
            });

            // Handle reject button click
            $(document).on('click', '.reject-btn', function() {
                var collectionId = $(this).data('id');
                $('#reject-form').attr('action', '{{ url("field-agent/collection") }}/' + collectionId + '/reject');
                $('#rejectModal').modal('show');
            });
        });
    </script>
@endsection
