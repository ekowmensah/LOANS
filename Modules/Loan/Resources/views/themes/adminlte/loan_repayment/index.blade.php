@extends('core::layouts.master')
@section('title')
    {{ trans_choice('loan::general.Repayments',2) }}
@endsection
@section('styles')
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trans_choice('loan::general.Repayments',2) }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('loan::general.Repayments',2) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                @can('loan.loans.create')
                    <a href="{{ url('loan/repayment/bulk/create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-plus"></i> {{ trans_choice('core::general.add',1) }} {{ trans_choice('loan::general.Bulk Repayment',1) }}
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
                    <form class="form-inline ml-0 ml-md-3" action="{{url('loan/repayment')}}">
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
                <table id="data-table" class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>
                            <a href="{{table_order_link('id')}}">
                                <span>{{ trans_choice('core::general.id',1) }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('amount')}}">
                                <span>{{ trans_choice('core::general.amount',1) }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('date')}}">
                                <span>{{ trans_choice('core::general.date',1) }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('client')}}">
                                <span>{{ trans_choice('client::general.client',1) }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{table_order_link('loan_id')}}">
                                <span>{{ trans_choice('loan::general.loan',1) }}</span>
                            </a>
                        </th>
                        <th>{{ trans_choice('core::general.action',1) }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key)
                        <tr>
                            <td>
                                <a href="{{url('loan/transaction/'.$key->id.'/show')}}">
                                    <span>{{$key->id}}</span>
                                </a>
                            </td>
                            <td>
                                <span>{{number_format($key->amount,2)}}</span>
                            </td>
                            <td>
                                <span>{{$key->submitted_on}}</span>
                            </td>
                            <td>
                                <a href="{{url('client/'.$key->id.'/show')}}">
                                    <span>{{$key->client}}</span>
                                </a>
                            </td>

                            <td>
                                <a href="{{url('loan/'.$key->loan_id.'/show')}}">
                                    <span>{{$key->loan_id}}</span>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button href="#" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{url('loan/transaction/' . $key->id . '/show')}}" class="dropdown-item">
                                            <i class="far fa-eye"></i>
                                            <span>{{trans_choice('core::general.detail',2)}}</span>
                                        </a>
                                        <a href="{{url('loan/transaction/' . $key->id . '/pdf') }}"
                                           target="_blank" class="dropdown-item"><i
                                                    class="fas fa-file-pdf"></i> {{ trans_choice('core::general.receipt', 1) }}
                                        </a>
                                        <a href="{{url('loan/transaction/' . $key->id . '/print') }}"
                                           target="_blank" class="dropdown-item"><i
                                                    class="fas fa-print"></i> {{ trans_choice('core::general.print', 1) }}
                                        </a>
                                        @can('loan.loans.transactions.edit')
                                            <a href="{{url('loan/repayment/' . $key->id . '/edit') }}"
                                               class="dropdown-item"><i
                                                        class="fas fa-edit"></i> {{ trans_choice('core::general.edit', 1) }}
                                            </a>

                                        @endcan
                                        @can('loan.loans.transactions.edit')

                                            <a href="{{url('loan/repayment/' . $key->id . '/reverse') }}"
                                               class="dropdown-item confirm"><i
                                                        class="fas fa-undo"></i> {{ trans_choice('loan::general.reverse', 1) }}
                                            </a>

                                        @endcan


                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
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

        </div>

    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: "#app",
            data: {
                records: {!!json_encode($data)!!},
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
