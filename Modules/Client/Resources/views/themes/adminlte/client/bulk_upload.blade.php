@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.bulk_upload',1) }} {{ trans_choice('client::general.client',2) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('core::general.bulk_upload',1) }} {{ trans_choice('client::general.client',2) }}
                        <a href="#" onclick="window.history.back()"
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
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.bulk_upload',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Instructions</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Important Information</h5>
                    <ul>
                        <li>Download the CSV template below and fill in your client data</li>
                        <li><strong>Required columns:</strong> first_name, last_name, gender, dob, branch_id, mobile</li>
                        <li>Date format should be: YYYY-MM-DD (e.g., 1990-01-15)</li>
                        <li>Gender values: male, female</li>
                        <li>Ghana Card format: GHA-XXXXXXXXX-X</li>
                        <li><strong>Default values:</strong> Client Type = Individual, Country = Ghana (leave empty in CSV)</li>
                        <li>Maximum file size: 5MB</li>
                        <li>The system will automatically create savings accounts for uploaded clients</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <a href="{{url('client/download-template')}}" class="btn btn-success">
                        <i class="fas fa-download"></i> Download CSV Template
                    </a>
                </div>

                @if(session('upload_errors'))
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Upload Errors</h5>
                        <ul>
                            @foreach(session('upload_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <form method="post" action="{{ url('client/validate-bulk-upload') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload CSV File</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="file" class="control-label">Select CSV File</label>
                        <input type="file" name="file" id="file" 
                               class="form-control @error('file') is-invalid @enderror" 
                               accept=".csv,.txt" required>
                        @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small class="form-text text-muted">
                            Accepted formats: CSV (.csv, .txt). Maximum size: 5MB
                        </small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Validate and Preview
                    </button>
                    <a href="{{url('client')}}" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">CSV Column Reference</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Column Name</th>
                                <th>Required</th>
                                <th>Description</th>
                                <th>Example</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>first_name</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Client's first name</td>
                                <td>John</td>
                            </tr>
                            <tr>
                                <td>last_name</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Client's last name</td>
                                <td>Doe</td>
                            </tr>
                            <tr>
                                <td>middle_name</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Client's middle name</td>
                                <td>K</td>
                            </tr>
                            <tr>
                                <td>gender</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Gender (male/female)</td>
                                <td>male</td>
                            </tr>
                            <tr>
                                <td>dob</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Date of birth (YYYY-MM-DD)</td>
                                <td>1990-01-15</td>
                            </tr>
                            <tr>
                                <td>branch_id</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Branch ID number</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>external_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>External reference ID</td>
                                <td>EXT001</td>
                            </tr>
                            <tr>
                                <td>ghana_card</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Ghana Card (National ID)</td>
                                <td>GHA-123456789-1</td>
                            </tr>
                            <tr>
                                <td>mobile</td>
                                <td><span class="badge badge-danger">Yes</span></td>
                                <td>Mobile phone number</td>
                                <td>0244123456</td>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Email address</td>
                                <td>john@example.com</td>
                            </tr>
                            <tr>
                                <td>address</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Physical address</td>
                                <td>123 Main St, Accra</td>
                            </tr>
                            <tr>
                                <td>marital_status</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Marital status</td>
                                <td>single</td>
                            </tr>
                            <tr>
                                <td>loan_officer_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Loan officer ID</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>title_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Title ID (Mr, Mrs, etc.)</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>profession_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Profession ID</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>client_type_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Client type ID (defaults to Individual if empty)</td>
                                <td>Leave empty</td>
                            </tr>
                            <tr>
                                <td>country_id</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Country ID (defaults to Ghana if empty)</td>
                                <td>Leave empty</td>
                            </tr>
                            <tr>
                                <td>notes</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Additional notes</td>
                                <td>Sample notes</td>
                            </tr>
                            <tr>
                                <td>created_date</td>
                                <td><span class="badge badge-secondary">No</span></td>
                                <td>Creation date (YYYY-MM-DD)</td>
                                <td>2024-12-08</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
