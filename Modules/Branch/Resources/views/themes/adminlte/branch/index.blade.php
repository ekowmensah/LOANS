@extends('core::layouts.master')
@section('title') {{ trans_choice('core::general.branch',2) }}
@endsection
@section('styles')
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('core::general.branch',2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.branch',2) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                @can('branch.branches.create')
                    <a href="{{ url('branch/create') }}"
                       class="btn btn-info btn-sm">
                        <i class="fas fa-plus"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('core::general.branch',1) }}
                    </a>
                @endcan
                <div class="btn-group">
                    <div class="dropdown">
                        <a href="#" class="btn btn-trigger btn-icon dropdown-toggle"
                           data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xs">
                            <a class="dropdown-item"><span>Show</span></a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>10])}}"
                               class="dropdown-item {{request('per_page')==10?'active':''}}">
                                10
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>20])}}"
                               class="dropdown-item {{(request('per_page')==20||!request('per_page'))?'active':''}}">
                                20
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['per_page'=>50])}}"
                               class="dropdown-item {{request('per_page')==50?'active':''}}">50</a>
                            <a class="dropdown-item">Order</a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'asc'])}}"
                               class="dropdown-item {{(request('order_by_dir')=='asc'||!request('order_by_dir'))?'active':''}}">
                                ASC
                            </a>
                            <a href="{{request()->fullUrlWithQuery(['order_by_dir'=>'desc'])}}"
                               class="dropdown-item {{request('order_by_dir')=='desc'?'active':''}}">
                                DESC
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-tools">
                    <form class="form-inline ml-0 ml-md-3" action="{{url('branch')}}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="s" class="form-control" value="{{request('s')}}"
                                   placeholder="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <table id="data-table" class="table table-striped table-hover table-sm">
                    <thead>
                    <tr>
                        <th>
                            <a href="{{table_order_link('name')}}">
                                {{ trans_choice('core::general.name',1) }}
                            </a>
                        </th>
                        <th class="text-center">Clients</th>
                        <th class="text-center">Groups</th>
                        <th class="text-center">Group Loans</th>
                        <th class="text-center">Individual Loans</th>
                        <th class="text-right">Total Savings</th>
                        <th class="text-right">Loan Disbursed</th>
                        <th class="text-right">Loan Paid</th>
                        <th class="text-right">Loan Outstanding</th>
                        <th>{{ trans_choice('core::general.action',1) }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key)
                        <tr>
                            <td>
                                <a href="{{url('branch/' . $key->id . '/show')}}" class="font-weight-bold">
                                    {{$key->name}}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{$key->total_clients}}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{$key->total_groups}}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{$key->total_group_loans}}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">{{$key->total_individual_loans}}</span>
                            </td>
                            <td class="text-right">
                                <strong class="text-info">{{number_format($key->total_savings, 2)}}</strong>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">{{number_format($key->loan_disbursed, 2)}}</strong>
                            </td>
                            <td class="text-right">
                                <strong class="text-primary">{{number_format($key->loan_paid, 2)}}</strong>
                            </td>
                            <td class="text-right">
                                <strong class="text-danger">{{number_format($key->loan_outstanding, 2)}}</strong>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button href="#" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{url('branch/' . $key->id . '/show')}}"
                                           class="dropdown-item">
                                            <i class="far fa-eye"></i>
                                            <span>{{trans_choice('core::general.detail',2)}}</span>
                                        </a>
                                        @can('branch.branches.edit')
                                            <a href="{{url('branch/' . $key->id . '/edit')}}"
                                               class="dropdown-item">
                                                <i class="far fa-edit"></i>
                                                <span>{{trans_choice('core::general.edit',1)}}</span>
                                            </a>
                                        @endcan
                                        <div class="dropdown-divider"></div>
                                        @can('branch.branches.destroy')
                                            <a href="{{url('branch/' . $key->id . '/destroy')}}"
                                               class="dropdown-item confirm">
                                                <i class="fas fa-trash"></i>
                                                <span>{{trans_choice('core::general.delete',1)}}</span>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th class="text-right">TOTALS:</th>
                            <th class="text-center">
                                <span class="badge badge-primary">{{$data->sum('total_clients')}}</span>
                            </th>
                            <th class="text-center">
                                <span class="badge badge-info">{{$data->sum('total_groups')}}</span>
                            </th>
                            <th class="text-center">
                                <span class="badge badge-success">{{$data->sum('total_group_loans')}}</span>
                            </th>
                            <th class="text-center">
                                <span class="badge badge-warning">{{$data->sum('total_individual_loans')}}</span>
                            </th>
                            <th class="text-right">
                                <strong class="text-info">{{number_format($data->sum('total_savings'), 2)}}</strong>
                            </th>
                            <th class="text-right">
                                <strong class="text-success">{{number_format($data->sum('loan_disbursed'), 2)}}</strong>
                            </th>
                            <th class="text-right">
                                <strong class="text-primary">{{number_format($data->sum('loan_paid'), 2)}}</strong>
                            </th>
                            <th class="text-right">
                                <strong class="text-danger">{{number_format($data->sum('loan_outstanding'), 2)}}</strong>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <div>{{ trans_choice('core::general.page',1) }} {{$data->currentPage()}} {{ trans_choice('core::general.of',1) }} {{$data->lastPage()}}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-center">
                            {{$data->links()}}
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                records:{!!json_encode($data)!!},
                selectAll: false,
                selectedRecords: []
            },
            methods: {
                selectAllRecords() {
                    this.selectedRecords = [];
                    if (this.selectAll) {
                        this.records.data.forEach(item => {
                            this.selectedRecords.push(item.id);
                        });
                    }
                },
            },
        })
    </script>
@endsection
