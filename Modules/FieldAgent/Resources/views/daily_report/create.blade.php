@extends('core::layouts.master')

@section('title')
    Submit {{ trans_choice('fieldagent::general.daily_report', 1) }}
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Submit {{ trans_choice('fieldagent::general.daily_report', 1) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ trans_choice('dashboard::general.dashboard', 1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('field-agent/daily-report') }}">{{ trans_choice('fieldagent::general.daily_report', 2) }}</a></li>
                        <li class="breadcrumb-item active">Submit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalCollections }}</h3>
                        <p>Total Collections</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalAmount, 0) }}</h3>
                        <p>Total Amount</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalClients }}</h3>
                        <p>Clients Paid</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $fieldAgent->agent_code }}</h3>
                        <p>{{ $fieldAgent->full_name }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daily Report - {{ now()->format('Y-m-d') }}</h3>
                <div class="card-tools">
                    <a href="{{ url('field-agent/daily-report') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> {{ trans_choice('core::general.back', 1) }}
                    </a>
                </div>
            </div>
            <form method="post" action="{{ url('field-agent/daily-report/store') }}">
                @csrf
                <input type="hidden" name="field_agent_id" value="{{ $fieldAgent->id }}">
                <input type="hidden" name="report_date" value="{{ now()->format('Y-m-d') }}">
                
                <div class="card-body">
                    <!-- Today's Collections Summary -->
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Today's Collections Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Collections:</strong> {{ $totalCollections }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Amount:</strong> {{ number_format($totalAmount, 2) }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Unique Clients:</strong> {{ $totalClients }}
                                </div>
                            </div>
                            
                            @if($collections->isNotEmpty())
                                <hr>
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Receipt</th>
                                            <th>Client</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($collections as $collection)
                                            <tr>
                                                <td>{{ $collection->receipt_number }}</td>
                                                <td>{{ $collection->client->first_name ?? 'N/A' }}</td>
                                                <td>{{ $collection->collection_type_label }}</td>
                                                <td>{{ number_format($collection->amount, 2) }}</td>
                                                <td>{!! $collection->status_badge !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-3">
                                    No collections recorded for today yet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cash Reconciliation -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="opening_cash_balance" class="control-label">
                                    {{ trans_choice('fieldagent::general.opening_balance', 1) }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="opening_cash_balance" id="opening_cash_balance" 
                                       class="form-control" value="{{ old('opening_cash_balance', 0) }}" 
                                       min="0" step="0.01" required>
                                <small class="form-text text-muted">Cash you started the day with</small>
                                @error('opening_cash_balance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="closing_cash_balance" class="control-label">
                                    {{ trans_choice('fieldagent::general.closing_balance', 1) }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="closing_cash_balance" id="closing_cash_balance" 
                                       class="form-control" value="{{ old('closing_cash_balance', 0) }}" 
                                       min="0" step="0.01" required>
                                <small class="form-text text-muted">Cash you have now</small>
                                @error('closing_cash_balance')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cash_deposited_to_branch" class="control-label">
                                    {{ trans_choice('fieldagent::general.cash_deposited', 1) }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="cash_deposited_to_branch" id="cash_deposited_to_branch" 
                                       class="form-control" value="{{ old('cash_deposited_to_branch', 0) }}" 
                                       min="0" step="0.01" required>
                                <small class="form-text text-muted">Cash deposited to branch/teller</small>
                                @error('cash_deposited_to_branch')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_clients_visited" class="control-label">
                                    {{ trans_choice('fieldagent::general.clients_visited', 1) }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="total_clients_visited" id="total_clients_visited" 
                                       class="form-control" value="{{ old('total_clients_visited', 0) }}" 
                                       min="0" required>
                                <small class="form-text text-muted">Total clients you visited today</small>
                                @error('total_clients_visited')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Variance Calculation Display -->
                    <div class="card bg-info" id="variance-card" style="display: none;">
                        <div class="card-body">
                            <h5>Cash Reconciliation</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Expected Cash:</strong><br>
                                        Opening (<span id="opening-display">0.00</span>) + Collections (<span id="collections-display">{{ number_format($totalAmount, 2) }}</span>) = 
                                        <strong><span id="expected-cash">0.00</span></strong>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Actual Cash:</strong><br>
                                        Closing (<span id="closing-display">0.00</span>) + Deposited (<span id="deposited-display">0.00</span>) = 
                                        <strong><span id="actual-cash">0.00</span></strong>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <h4>Variance: <span id="variance-display" class="badge badge-light">0.00</span></h4>
                            <p id="variance-message"></p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">{{ trans_choice('core::general.notes', 1) }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                <small class="form-text text-muted">Any additional notes or comments</small>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Submit Daily Report
                    </button>
                    <a href="{{ url('field-agent/daily-report') }}" class="btn btn-default">
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
            var totalCollections = {{ $totalAmount }};

            function calculateVariance() {
                var opening = parseFloat($('#opening_cash_balance').val()) || 0;
                var closing = parseFloat($('#closing_cash_balance').val()) || 0;
                var deposited = parseFloat($('#cash_deposited_to_branch').val()) || 0;

                var expectedCash = opening + totalCollections;
                var actualCash = closing + deposited;
                var variance = actualCash - expectedCash;

                // Update display
                $('#opening-display').text(opening.toFixed(2));
                $('#closing-display').text(closing.toFixed(2));
                $('#deposited-display').text(deposited.toFixed(2));
                $('#expected-cash').text(expectedCash.toFixed(2));
                $('#actual-cash').text(actualCash.toFixed(2));
                $('#variance-display').text(variance.toFixed(2));

                // Show variance card
                $('#variance-card').show();

                // Update variance message and color
                if (Math.abs(variance) < 0.01) {
                    $('#variance-display').removeClass('badge-danger badge-warning').addClass('badge-success');
                    $('#variance-message').html('<i class="fas fa-check-circle"></i> Perfect! No variance.');
                } else if (variance > 0) {
                    $('#variance-display').removeClass('badge-success badge-warning').addClass('badge-warning');
                    $('#variance-message').html('<i class="fas fa-exclamation-triangle"></i> You have excess cash of ' + Math.abs(variance).toFixed(2));
                } else {
                    $('#variance-display').removeClass('badge-success badge-warning').addClass('badge-danger');
                    $('#variance-message').html('<i class="fas fa-exclamation-circle"></i> You are short by ' + Math.abs(variance).toFixed(2));
                }
            }

            // Calculate variance on input change
            $('#opening_cash_balance, #closing_cash_balance, #cash_deposited_to_branch').on('input', function() {
                calculateVariance();
            });
        });
    </script>
@endsection
