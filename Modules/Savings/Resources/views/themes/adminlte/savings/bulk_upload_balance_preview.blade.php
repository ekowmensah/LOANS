@extends('core::layouts.master')
@section('title')
    Bulk Upload Balance - Preview
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bulk Upload Balance - Preview</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings')}}">{{ trans_choice('savings::general.savings',2) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings/bulk-upload-balance')}}">Bulk Upload Balance</a></li>
                        <li class="breadcrumb-item active">Preview</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Review Upload Data</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info-circle"></i> Summary</h5>
                            <p class="mb-0">
                                <strong>Total Rows:</strong> {{ count($validRows) + count($invalidRows) }} |
                                <strong class="text-success">Valid:</strong> {{ count($validRows) }} |
                                <strong class="text-danger">Invalid:</strong> {{ count($invalidRows) }}
                            </p>
                        </div>
                    </div>
                </div>

                @if(count($invalidRows) > 0)
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Invalid Rows Found</h5>
                        <p>The following rows have errors and will not be processed. Please fix these errors and upload again.</p>
                    </div>

                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Invalid Rows ({{ count($invalidRows) }})</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Row #</th>
                                        <th>Account Number</th>
                                        <th>Client Name</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                        <th>Errors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invalidRows as $row)
                                        <tr>
                                            <td>{{ $row['row_number'] }}</td>
                                            <td>{{ $row['account_number'] ?? 'N/A' }}</td>
                                            <td>{{ $row['client_name'] ?? 'N/A' }}</td>
                                            <td>{{ $row['balance_brought_forward'] ?? 'N/A' }}</td>
                                            <td>{{ $row['transaction_date'] ?? 'N/A' }}</td>
                                            <td>
                                                <ul class="mb-0 pl-3">
                                                    @foreach($row['errors'] as $error)
                                                        <li class="text-danger">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(count($validRows) > 0)
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Valid Rows ({{ count($validRows) }})</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Row #</th>
                                        <th>Account Number</th>
                                        <th>Client Name</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($validRows as $row)
                                        <tr>
                                            <td>{{ $row['row_number'] }}</td>
                                            <td><span class="badge badge-info">{{ $row['account_number'] }}</span></td>
                                            <td>{{ $row['client_name'] ?? 'N/A' }}</td>
                                            <td><strong>{{ number_format($row['balance_brought_forward'], 2) }}</strong></td>
                                            <td>{{ $row['transaction_date'] }}</td>
                                            <td>{{ $row['notes'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th colspan="3" class="text-right">Total Balance to Upload:</th>
                                        <th colspan="3">
                                            <strong class="text-success">
                                                {{ number_format(array_sum(array_column($validRows, 'balance_brought_forward')), 2) }}
                                            </strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{url('savings/bulk-upload-balance/process')}}">
                                {{csrf_field()}}
                                <div class="alert alert-warning">
                                    <i class="icon fas fa-exclamation-triangle"></i>
                                    <strong>Warning:</strong> This action will create deposit transactions for {{ count($validRows) }} savings account(s). 
                                    This cannot be undone automatically. Please review carefully before proceeding.
                                </div>
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to process {{ count($validRows) }} balance upload(s)?')">
                                    <i class="fas fa-check-circle"></i> Process {{ count($validRows) }} Balance(s)
                                </button>
                                <a href="{{url('savings/bulk-upload-balance')}}" class="btn btn-default btn-lg">
                                    <i class="fas fa-arrow-left"></i> Back to Upload
                                </a>
                                <a href="{{url('savings')}}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> No Valid Rows</h5>
                        <p>There are no valid rows to process. Please fix the errors and upload again.</p>
                        <a href="{{url('savings/bulk-upload-balance')}}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Upload
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
