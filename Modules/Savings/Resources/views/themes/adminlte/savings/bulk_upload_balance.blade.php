@extends('core::layouts.master')
@section('title')
    Bulk Upload Balance Brought Forward
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bulk Upload Balance Brought Forward</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{url('savings')}}">{{ trans_choice('savings::general.savings',2) }}</a></li>
                        <li class="breadcrumb-item active">Bulk Upload Balance</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Balance Brought Forward</h3>
                <div class="card-tools">
                    <a href="{{url('savings/bulk-upload-balance/template')}}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                    <a href="{{url('savings')}}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Instructions</h5>
                            <ol>
                                <li>Download the CSV template using the button above</li>
                                <li>Fill in the required information:
                                    <ul>
                                        <li><strong>account_number</strong>: The savings account number (required)</li>
                                        <li><strong>client_name</strong>: Client name for reference (optional, for your reference only)</li>
                                        <li><strong>balance_brought_forward</strong>: The opening balance amount (required)</li>
                                        <li><strong>transaction_date</strong>: Date of the balance (YYYY-MM-DD format, required)</li>
                                        <li><strong>notes</strong>: Optional notes about the balance</li>
                                    </ul>
                                </li>
                                <li>Save the file as CSV format</li>
                                <li>Upload the file using the form below</li>
                                <li>Review the preview and confirm to process</li>
                            </ol>
                            <p class="mb-0"><strong>Note:</strong> Only active savings accounts can have balance brought forward uploaded.</p>
                        </div>

                        <form method="post" action="{{url('savings/bulk-upload-balance/validate')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            
                            <div class="form-group">
                                <label for="file" class="control-label">
                                    Select CSV File <span class="text-danger">*</span>
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" required accept=".csv,.txt">
                                    <label class="custom-file-label" for="file">Choose file</label>
                                </div>
                                <small class="form-text text-muted">
                                    Maximum file size: 5MB. Accepted formats: CSV, TXT
                                </small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload and Validate
                                </button>
                                <a href="{{url('savings')}}" class="btn btn-default">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Update file input label with selected filename
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endsection
