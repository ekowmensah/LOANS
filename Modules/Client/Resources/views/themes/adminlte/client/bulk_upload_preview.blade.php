@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.bulk_upload',1) }} {{ trans_choice('client::general.client',2) }} - Preview
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Bulk Upload Preview
                        <a href="{{url('client/bulk-upload')}}"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em><span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('client')}}">{{ trans_choice('client::general.client',2) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('client/bulk-upload')}}">{{ trans_choice('core::general.bulk_upload',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">Preview</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <!-- Summary Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Summary</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-file-csv"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Rows</span>
                                <span class="info-box-number">{{ $uploadData['total_rows'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Valid Rows</span>
                                <span class="info-box-number">{{ $uploadData['valid_count'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Invalid Rows</span>
                                <span class="info-box-number">{{ $uploadData['invalid_count'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Success Rate</span>
                                <span class="info-box-number">
                                    {{ $uploadData['total_rows'] > 0 ? round(($uploadData['valid_count'] / $uploadData['total_rows']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($uploadData['valid_count'] > 0)
                    <div class="alert alert-success">
                        <h5><i class="icon fas fa-check"></i> Ready to Import</h5>
                        <p>{{ $uploadData['valid_count'] }} client(s) are ready to be imported. Click "Proceed with Import" below to continue.</p>
                    </div>
                @endif

                @if($uploadData['invalid_count'] > 0)
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Validation Errors Found</h5>
                        <p>{{ $uploadData['invalid_count'] }} row(s) have validation errors and will be skipped during import. Please review the errors below.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Invalid Rows -->
        @if($uploadData['invalid_count'] > 0)
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Invalid Rows ({{ $uploadData['invalid_count'] }})</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Row #</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>External ID</th>
                                    <th>Ghana Card</th>
                                    <th>Errors</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($uploadData['invalid_rows'] as $row)
                                    <tr>
                                        <td><span class="badge badge-danger">{{ $row['row_number'] }}</span></td>
                                        <td>{{ $row['data']['first_name'] ?? '-' }}</td>
                                        <td>{{ $row['data']['last_name'] ?? '-' }}</td>
                                        <td>{{ $row['data']['mobile'] ?? '-' }}</td>
                                        <td>{{ $row['data']['external_id'] ?? '-' }}</td>
                                        <td>{{ $row['data']['ghana_card'] ?? '-' }}</td>
                                        <td>
                                            <ul class="mb-0 pl-3">
                                                @foreach($row['errors'] as $error)
                                                    <li class="text-danger small">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Valid Rows Preview -->
        @if($uploadData['valid_count'] > 0)
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Valid Rows - Preview ({{ $uploadData['valid_count'] }})</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Row #</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Gender</th>
                                    <th>DOB</th>
                                    <th>Mobile</th>
                                    <th>Branch ID</th>
                                    <th>Ghana Card</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($uploadData['valid_rows'], 0, 20) as $row)
                                    <tr>
                                        <td><span class="badge badge-success">{{ $row['row_number'] }}</span></td>
                                        <td>{{ $row['data']['first_name'] }}</td>
                                        <td>{{ $row['data']['last_name'] }}</td>
                                        <td>{{ ucfirst($row['data']['gender']) }}</td>
                                        <td>{{ $row['data']['dob'] }}</td>
                                        <td>{{ $row['data']['mobile'] }}</td>
                                        <td>{{ $row['data']['branch_id'] }}</td>
                                        <td>{{ $row['data']['ghana_card'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                @if(count($uploadData['valid_rows']) > 20)
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <em>... and {{ count($uploadData['valid_rows']) - 20 }} more row(s)</em>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if($uploadData['valid_count'] > 0)
                            <form method="post" action="{{ url('client/process-bulk-upload') }}">
                                {{csrf_field()}}
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-upload"></i> Proceed with Import ({{ $uploadData['valid_count'] }} clients)
                                </button>
                                <a href="{{url('client/bulk-upload')}}" class="btn btn-default btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </form>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Cannot Proceed</h5>
                                <p>No valid rows found. Please fix the errors in your CSV file and try again.</p>
                            </div>
                            <a href="{{url('client/bulk-upload')}}" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-left"></i> Back to Upload
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
