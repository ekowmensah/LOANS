@extends('core::layouts.master')
@section('title')
    {{ trans_choice('core::general.edit',1) }}  {{ trans_choice('loan::general.schedule',2) }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ trans_choice('core::general.edit',1) }} {{ trans_choice('loan::general.loan',1) }}
                        <a href="#" onclick="window.history.back()"
                           class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                            <em class="icon ni ni-arrow-left"></em>
                            <span>{{ trans_choice('core::general.back',1) }}</span>
                        </a>
                    </h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                    href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('loan')}}">{{ trans_choice('loan::general.loan',2) }}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                    href="{{url('loan/'.$loan->id.'/show')}}">{{ trans_choice('loan::general.loan',1) }}
                                #{{$loan->id}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ trans_choice('core::general.edit',1) }} {{ trans_choice('loan::general.schedule',1) }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content" id="app">
        <form method="post" action="{{ url('loan/'.$loan->id.'/schedule/update') }}">
            {{csrf_field()}}
            <div class="card card-bordered card-preview">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Due Date</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>Fees</th>
                            <th>Penalty</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loan->repayment_schedules as $schedule)
                            <tr>
                                <td>
                                    {{$schedule->id}}
                                </td>
                                <td>
                                    <flat-pickr
                                            class="form-control"
                                            name="due_date[{{$schedule->id}}][]" value="{{$schedule->due_date}}" required>
                                    </flat-pickr>
                                </td>
                                <td>
                                    <input name="principal[{{$schedule->id}}][]" class="form-control"
                                           value="{{round($schedule->principal,$loan->decimals)}}"/>
                                </td>
                                <td>
                                    <input name="interest[{{$schedule->id}}][]" class="form-control"
                                           value="{{round($schedule->interest,$loan->decimals)}}"/>
                                </td>
                                <td>
                                    <input name="fees[{{$schedule->id}}][]" class="form-control"
                                           value="{{round($schedule->fees,$loan->decimals)}}"/>
                                </td>
                                <td>
                                    <input name="penalties[{{$schedule->id}}][]" class="form-control"
                                           value="{{round($schedule->penalty,$loan->decimals)}}"/>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="card-footer border-top ">
                    <button type="submit"
                            class="btn btn-primary  float-right">{{trans_choice('core::general.save',1)}}</button>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                stage: 1,
                loan: @json($loan),


            },
            created: function () {
                //this.loan_charges=charges;

            },
            methods: {
                add_charge(event) {
                    if (this.selected_charge != '') {
                        this.selected_charges.push(original_charges[this.selected_charge]);
                        delete charges[this.selected_charge];
                        this.selected_charge = '';
                    } else {
                        alert('Please select a charge')
                    }
                },
                remove_charge(event) {
                    var id = event.currentTarget.getAttribute('data-id');
                    this.selected_charges.splice(id, 1);
                    //charges.push(original_charges[id]);
                },
                onSubmit() {

                }
            }
        });
    </script>
@endsection