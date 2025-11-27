<style>
    body{
        font-size: 9px;
    }
    .table {
        width: 100%;
        border: 1px solid #ccc;
        border-collapse: collapse;
    }

    .table th, td {
        padding: 5px;
        text-align: left;
        border: 1px solid #ccc;
    }

    .light-heading th {
        background-color: #eeeeee
    }

    .green-heading th {
        background-color: #4CAF50;
        color: white;
    }

    .text-center {
        text-align: center;
    }

    .table-striped tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .text-danger {
        color: #a94442;
    }
    .text-success {
        color: #3c763d;
    }

</style>
<h3 class="text-center">{{\Modules\Setting\Entities\Setting::where('setting_key','core.company_name')->first()->setting_value}}</h3>
<h3 class="text-center"> {{trans_choice('loan::general.collection_sheet',1)}}</h3>
<p class="text-center"><b>Period: {{$start_date}} to {{$end_date}}</b></p>

<!-- Individual Loans Section -->
@if($individual_loans->isNotEmpty())
<h4>Individual Loans ({{$individual_loans->count()}})</h4>
<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr class="green-heading">
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
    @foreach($individual_loans as $loan)
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
                    <span class="text-danger"><b>[OVERDUE]</b></span>
                @endif
            </td>
            <td>{{ number_format($loan->expected_amount, 2) }}</td>
            <td>{{ number_format($loan->total_due, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="8"><b>{{trans_choice('core::general.total',1)}}</b></td>
        <td><b>{{number_format($individual_total_expected, 2)}}</b></td>
        <td><b>{{number_format($individual_total_due, 2)}}</b></td>
    </tr>
    </tfoot>
</table>
@endif

<!-- Group Loans Section -->
@if($group_loans->isNotEmpty())
<h4 style="margin-top: 20px;">Group Loans ({{$group_loans->count()}})</h4>
<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr class="green-heading">
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
    @foreach($group_loans as $loan)
        <?php
        $group_total_due += $loan->total_due;
        $group_total_expected += $loan->expected_amount;
        ?>
        <!-- Group Loan Row -->
        <tr class="light-heading">
            <td>{{ $loan->loan_officer }}</td>
            <td>{{ $loan->branch }}</td>
            <td><b>{{ $loan->group_name }}</b></td>
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
                    <span class="text-danger"><b>[OVERDUE]</b></span>
                @endif
            </td>
            <td>{{ number_format($loan->expected_amount, 2) }}</td>
            <td>{{ number_format($loan->total_due, 2) }}</td>
        </tr>
        <!-- Group Members -->
        @if(!empty($loan->members) && $loan->members->isNotEmpty())
            @foreach($loan->members as $member)
        <tr>
            <td colspan="2" style="padding-left: 20px;">Member</td>
            <td>{{ $member->member_name }}</td>
            <td>{{ $member->member_mobile }}</td>
            <td colspan="4"></td>
            <td>{{ number_format($member->member_expected_amount, 2) }}</td>
            <td>{{ number_format($member->member_total_due, 2) }}</td>
        </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="8"><b>{{trans_choice('core::general.total',1)}}</b></td>
        <td><b>{{number_format($group_total_expected, 2)}}</b></td>
        <td><b>{{number_format($group_total_due, 2)}}</b></td>
    </tr>
    </tfoot>
</table>
@endif

<!-- Grand Total Summary -->
@if($individual_loans->isNotEmpty() || $group_loans->isNotEmpty())
<h4 style="margin-top: 20px;">Summary</h4>
<table class="table table-bordered">
    <tr>
        <td><b>Total Individual Loans:</b></td>
        <td>{{$individual_loans->count()}}</td>
        <td><b>Total Group Loans:</b></td>
        <td>{{$group_loans->count()}}</td>
    </tr>
    <tr>
        <td><b>Individual Expected Amount:</b></td>
        <td>{{number_format($individual_loans->sum('expected_amount'), 2)}}</td>
        <td><b>Group Expected Amount:</b></td>
        <td>{{number_format($group_loans->sum('expected_amount'), 2)}}</td>
    </tr>
    <tr>
        <td><b>Individual Total Due:</b></td>
        <td>{{number_format($individual_loans->sum('total_due'), 2)}}</td>
        <td><b>Group Total Due:</b></td>
        <td>{{number_format($group_loans->sum('total_due'), 2)}}</td>
    </tr>
    <tr class="green-heading">
        <td><b>GRAND TOTAL LOANS:</b></td>
        <td><b>{{$individual_loans->count() + $group_loans->count()}}</b></td>
        <td><b>GRAND TOTAL EXPECTED:</b></td>
        <td><b>{{number_format($individual_loans->sum('expected_amount') + $group_loans->sum('expected_amount'), 2)}}</b></td>
    </tr>
    <tr class="green-heading">
        <td colspan="2"></td>
        <td><b>GRAND TOTAL DUE:</b></td>
        <td><b>{{number_format($individual_loans->sum('total_due') + $group_loans->sum('total_due'), 2)}}</b></td>
    </tr>
</table>
@endif