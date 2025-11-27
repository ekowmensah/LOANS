@extends('core::layouts.master')
@section('title')
    {{trans_choice('loan::general.collection_sheet',1)}}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans_choice('loan::general.collection_sheet',1)}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('report')}}">{{ trans_choice('report::general.report',2) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('report/loan')}}">{{trans_choice('loan::general.loan',1)}} {{trans_choice('report::general.report',2)}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans_choice('loan::general.collection_sheet',1)}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">
                    {{trans_choice('loan::general.collection_sheet',1)}}
                    @if(!empty($start_date))
                        for period: <b>{{$start_date}} to {{$end_date}}</b>
                    @endif
                </h3>
                <div class="card-tools hidden-print">
                    <div class="dropdown">
                        <a href="#" class="btn btn-info btn-trigger btn-icon dropdown-toggle"
                           data-toggle="dropdown">
                            {{trans_choice('core::general.action',2)}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                            <a href="{{url('report/loan/collection_sheet?download=1&type=csv&start_date='.$start_date.'&end_date='.$end_date.'&branch_id='.$branch_id.'&loan_officer_id='.$loan_officer_id.'&loan_product_id='.$loan_product_id)}}"
                               class="dropdown-item">{{trans_choice('core::general.download',1)}} {{trans_choice('core::general.csv_format',1)}}</a>
                            <a href="{{url('report/loan/collection_sheet?download=1&type=excel&start_date='.$start_date.'&end_date='.$end_date.'&branch_id='.$branch_id.'&loan_officer_id='.$loan_officer_id.'&loan_product_id='.$loan_product_id)}}"
                               class="dropdown-item">{{trans_choice('core::general.download',1)}} {{trans_choice('core::general.excel_format',1)}}</a>
                            <a href="{{url('report/loan/collection_sheet?download=1&type=excel_2007&start_date='.$start_date.'&end_date='.$end_date.'&branch_id='.$branch_id.'&loan_officer_id='.$loan_officer_id.'&loan_product_id='.$loan_product_id)}}"
                               class="dropdown-item">{{trans_choice('core::general.download',1)}} {{trans_choice('core::general.excel_2007_format',1)}}</a>
                            <a href="{{url('report/loan/collection_sheet?download=1&type=pdf&start_date='.$start_date.'&end_date='.$end_date.'&branch_id='.$branch_id.'&loan_officer_id='.$loan_officer_id.'&loan_product_id='.$loan_product_id)}}"
                               class="dropdown-item">{{trans_choice('core::general.download',1)}} {{trans_choice('core::general.pdf_format',1)}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="get" action="{{Request::url()}}" class="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="branch_id">{{trans_choice('core::general.branch',1)}}</label>
                                <select class="form-control select2" name="branch_id" id="branch_id">
                                    <option value="" disabled
                                            selected>{{trans_choice('core::general.select',1)}}</option>
                                    @foreach($branches as $key)
                                        <option value="{{$key->id}}"
                                                @if($branch_id==$key->id) selected @endif>{{$key->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="start_date">{{trans_choice('core::general.start_date',1)}}</label>
                                <flat-pickr value="{{$start_date}}"
                                            class="form-control  @error('start_date') is-invalid @enderror"
                                            name="start_date" id="start_date" required>
                                </flat-pickr>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="end_date">{{trans_choice('core::general.end_date',1)}}</label>
                                <flat-pickr value="{{$end_date}}"
                                            class="form-control  @error('end_date') is-invalid @enderror"
                                            name="end_date" id="end_date" required>
                                </flat-pickr>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="loan_officer_id">{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.officer',1)}}</label>
                                <select class="form-control select2" name="loan_officer_id" id="loan_officer_id">
                                    <option value="" disabled
                                            selected>{{trans_choice('core::general.select',1)}}</option>
                                    @foreach($users as $key)
                                        <option value="{{$key->id}}"
                                                @if($loan_officer_id==$key->id) selected @endif>{{$key->first_name}} {{$key->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="loan_product_id">{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.product',1)}}</label>
                                <select class="form-control select2" name="loan_product_id" id="loan_product_id">
                                    <option value="" disabled
                                            selected>{{trans_choice('core::general.select',1)}}</option>
                                    @foreach($loan_products as $key)
                                        <option value="{{$key->id}}"
                                                @if($loan_product_id==$key->id) selected @endif>{{$key->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="gender">{{trans_choice('core::general.gender',1)}}</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="" disabled
                                            selected>{{trans_choice('core::general.select',1)}}</option>
                                    <option value="male"
                                            @if($gender=='male') selected @endif>{{trans_choice('core::general.male',1)}} </option>
                                    <option value="female"
                                            @if($gender=='female') selected @endif>{{trans_choice('core::general.female',1)}}</option>
                                    <option value="unspecified"
                                            @if($gender=='unspecified') selected @endif>{{trans_choice('core::general.unspecified',1)}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="age_from">{{trans_choice('client::general.age_from',1)}}</label>
                                <input value="{{$age_from}}" type="number"
                                            class="form-control  @error('age_from') is-invalid @enderror"
                                            name="age_from" id="age_from"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"
                                       for="age_to">{{trans_choice('client::general.age_to',1)}}</label>
                                <input value="{{$age_to}}" type="number"
                                            class="form-control  @error('age_to') is-invalid @enderror"
                                            name="age_to" id="age_to"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                        <span class="input-group-btn">
                          <button type="submit" class="btn bg-olive btn-flat">{{trans_choice('core::general.filter',1)}}
                          </button>
                        </span>
                            <span class="input-group-btn">
                          <a href="{{Request::url()}}"
                             class="btn bg-purple  btn-flat pull-right">{{trans_choice('general.reset',1)}}!</a>
                        </span>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->
        @if(!empty($start_date))
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#individual_loans" data-toggle="tab">Individual Loans ({{$individual_loans->count()}})</a></li>
                        <li class="nav-item"><a class="nav-link" href="#group_loans" data-toggle="tab">Group Loans ({{$group_loans->count()}})</a></li>
                        <li class="nav-item"><a class="nav-link" href="#summary" data-toggle="tab">Summary</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Individual Loans Tab -->
                        <div class="active tab-pane" id="individual_loans">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr style="background-color: #D1F9FF">
                                        <th>{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.officer',1)}}</th>
                                        <th>{{trans_choice('core::general.branch',1)}}</th>
                                        <th>{{trans_choice('client::general.client',1)}}</th>
                                        <th>{{trans_choice('core::general.mobile',1)}}</th>
                                        <th>{{trans_choice('loan::general.loan',1)}}#</th>
                                        <th>{{trans_choice('loan::general.product',1)}}</th>
                                        @if(!empty($individual_loans->first()->custom_fields))
                                            @foreach($individual_loans->first()->custom_fields as $k=>$v)
                                                <th>{{$k}}</th>
                                            @endforeach
                                        @endif
                                        <th>{{ trans_choice('loan::general.expected',1) }} {{ trans_choice('loan::general.maturity',1) }}</th>
                                        <th>Next {{trans_choice('loan::general.repayment',1)}} {{ trans_choice('core::general.date',1) }}</th>
                                        <th>{{ trans_choice('loan::general.expected',1) }} {{trans_choice('loan::general.amount',1)}}</th>
                                        <th>{{ trans_choice('loan::general.total',1) }} {{trans_choice('loan::general.due',1)}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $individual_total_due = 0;
                                        $individual_total_expected = 0;
                                        ?>
                                    @forelse($individual_loans as $loan)
                                            <?php
                                            $individual_total_due += $loan->total_due;
                                            $individual_total_expected += $loan->expected_amount;
                                            ?>
                                        <tr>
                                            <td>{{ $loan->loan_officer }}</td>
                                            <td>{{ $loan->branch }}</td>
                                            <td>{{ $loan->client }}</td>
                                            <td>{{ $loan->mobile }}</td>
                                            <td>{{ $loan->loan_id }}</td>
                                            <td>{{ $loan->loan_product }}</td>
                                            @if(!empty($loan->custom_fields))
                                                @foreach($loan->custom_fields as $v)
                                                    <td>{{$v}}</td>
                                                @endforeach
                                            @endif
                                            <td>{{ $loan->expected_maturity_date }}</td>
                                            <td>
                                                {{ $loan->due_date }}
                                                @if($loan->total_due > 0 && \Carbon\Carbon::parse($loan->due_date)->isPast())
                                                    <span class="badge badge-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($loan->expected_amount, 2) }}</td>
                                            <td>{{ number_format($loan->total_due, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No individual loans found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($individual_loans->isNotEmpty())
                                    <tfoot>
                                    <tr>
                                        <td colspan="8"><b>{{trans_choice('core::general.total',1)}}</b></td>
                                        <td><b>{{number_format($individual_total_expected, 2)}}</b></td>
                                        <td><b>{{number_format($individual_total_due, 2)}}</b></td>
                                    </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Group Loans Tab -->
                        <div class="tab-pane" id="group_loans">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr style="background-color: #D1F9FF">
                                        <th>{{trans_choice('loan::general.loan',1)}} {{trans_choice('loan::general.officer',1)}}</th>
                                        <th>{{trans_choice('core::general.branch',1)}}</th>
                                        <th>{{trans_choice('client::general.group',1)}}/Member</th>
                                        <th>{{trans_choice('core::general.mobile',1)}}</th>
                                        <th>{{trans_choice('loan::general.loan',1)}}#</th>
                                        <th>{{trans_choice('loan::general.product',1)}}</th>
                                        @if(!empty($group_loans->first()->custom_fields))
                                            @foreach($group_loans->first()->custom_fields as $k=>$v)
                                                <th>{{$k}}</th>
                                            @endforeach
                                        @endif
                                        <th>{{ trans_choice('loan::general.expected',1) }} {{ trans_choice('loan::general.maturity',1) }}</th>
                                        <th>Next {{trans_choice('loan::general.repayment',1)}} {{ trans_choice('core::general.date',1) }}</th>
                                        <th>{{ trans_choice('loan::general.expected',1) }} {{trans_choice('loan::general.amount',1)}}</th>
                                        <th>{{ trans_choice('loan::general.total',1) }} {{trans_choice('loan::general.due',1)}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $group_total_due = 0;
                                        $group_total_expected = 0;
                                        ?>
                                    @forelse($group_loans as $loan)
                                            <?php
                                            $group_total_due += $loan->total_due;
                                            $group_total_expected += $loan->expected_amount;
                                            ?>
                                        <!-- Group Loan Row -->
                                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                                            <td>{{ $loan->loan_officer }}</td>
                                            <td>{{ $loan->branch }}</td>
                                            <td><i class="fas fa-users"></i> {{ $loan->group_name }}</td>
                                            <td>-</td>
                                            <td>{{ $loan->loan_id }}</td>
                                            <td>{{ $loan->loan_product }}</td>
                                            @if(!empty($loan->custom_fields))
                                                @foreach($loan->custom_fields as $v)
                                                    <td>{{$v}}</td>
                                                @endforeach
                                            @endif
                                            <td>{{ $loan->expected_maturity_date }}</td>
                                            <td>
                                                {{ $loan->due_date }}
                                                @if($loan->total_due > 0 && \Carbon\Carbon::parse($loan->due_date)->isPast())
                                                    <span class="badge badge-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($loan->expected_amount, 2) }}</td>
                                            <td>{{ number_format($loan->total_due, 2) }}</td>
                                        </tr>
                                        <!-- Group Members -->
                                        @if(!empty($loan->members) && $loan->members->isNotEmpty())
                                            @foreach($loan->members as $member)
                                        <tr style="background-color: #fcfcfc;">
                                            <td colspan="2" style="padding-left: 30px;"><i class="fas fa-user"></i> Member</td>
                                            <td>{{ $member->member_name }}</td>
                                            <td>{{ $member->member_mobile }}</td>
                                            <td colspan="4"></td>
                                            <td>{{ number_format($member->member_expected_amount, 2) }}</td>
                                            <td>{{ number_format($member->member_total_due, 2) }}</td>
                                        </tr>
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No group loans found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($group_loans->isNotEmpty())
                                    <tfoot>
                                    <tr>
                                        <td colspan="8"><b>{{trans_choice('core::general.total',1)}}</b></td>
                                        <td><b>{{number_format($group_total_expected, 2)}}</b></td>
                                        <td><b>{{number_format($group_total_due, 2)}}</b></td>
                                    </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- Summary Tab -->
                        <div class="tab-pane" id="summary">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Individual Loans Summary</h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Total Repayment Schedules:</td>
                                                    <td><b>{{$individual_loans->count()}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Unique Loans:</td>
                                                    <td><b>{{$individual_loans->pluck('loan_id')->unique()->count()}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Expected Amount:</td>
                                                    <td><b>{{number_format($individual_loans->sum('expected_amount'), 2)}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Due:</td>
                                                    <td><b>{{number_format($individual_loans->sum('total_due'), 2)}}</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Group Loans Summary</h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Total Repayment Schedules:</td>
                                                    <td><b>{{$group_loans->count()}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Unique Loans:</td>
                                                    <td><b>{{$group_loans->pluck('loan_id')->unique()->count()}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Expected Amount:</td>
                                                    <td><b>{{number_format($group_loans->sum('expected_amount'), 2)}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Due:</td>
                                                    <td><b>{{number_format($group_loans->sum('total_due'), 2)}}</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">Grand Total</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Total Repayment Schedules:</td>
                                            <td><b>{{$individual_loans->count() + $group_loans->count()}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Total Unique Loans:</td>
                                            <td><b>{{$individual_loans->pluck('loan_id')->unique()->count() + $group_loans->pluck('loan_id')->unique()->count()}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Total Expected Amount:</td>
                                            <td><b>{{number_format($individual_loans->sum('expected_amount') + $group_loans->sum('expected_amount'), 2)}}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Total Due:</td>
                                            <td><b>{{number_format($individual_loans->sum('total_due') + $group_loans->sum('total_due'), 2)}}</b></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {},
            methods: {},
        })
    </script>
@endsection
