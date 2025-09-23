<html lang="en">
<head>
    <title>{{ trans_choice('savings::general.transaction',1) }}  {{ trans_choice('core::general.detail',2) }}</title>
    <style>
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            display: table;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .pull-right {
            float: right !important;
        }

        span {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="text-center">
    @if(!empty($company_logo))
        <img src="{{asset('storage/uploads/'.$company_logo)}}" alt="logo" height="100"/>
    @endif
    <h3 class="text-center">{{$company_name}}</h3>
</div>
<div>
    <table class="table  table-bordered table-hover table-striped" id="">
        <tbody>
        <tr>
            <td>{{ trans_choice('client::general.client',1) }}</td>
            <td class="text-right">
                @if(!empty($savings_transaction->savings->client))
                    {{$savings_transaction->savings->client->first_name}} {{$savings_transaction->savings->client->middle_name}} {{$savings_transaction->savings->client->last_name}}
                @endif
            </td>
        </tr>
        <tr>
            <td>{{ trans_choice('savings::general.transaction',1) }}  {{ trans_choice('core::general.id',1) }}</td>
            <td class="text-right">{{$savings_transaction->id}}</td>
        </tr>
        <tr>
            <td>{{ trans_choice('savings::general.transaction',1) }}  {{ trans_choice('core::general.type',1) }}</td>
            <td class="text-right">
                {{$savings_transaction->name}}
            </td>
        </tr>
        <tr>
            <td>{{ trans_choice('core::general.date',1) }}</td>
            <td class="text-right">{{$savings_transaction->submitted_on}}</td>
        </tr>
        <tr>
            <td>{{ trans_choice('core::general.amount',1) }}</td>
            <td class="text-right">
                {{number_format($savings_transaction->amount,$savings_transaction->savings->decimals)}}
            </td>
        </tr>
        <tr>
            <td>{{ trans_choice('savings::general.interest_rate',1) }}</td>
            <td class="text-right">
                {{number_format($savings_transaction->savings->interest_rate,2)}}%
            </td>
        </tr>
        <tr>
            <td>{{ trans_choice('savings::general.duration',1) }}</td>
            <td class="text-right">
                {{$savings_transaction->savings->lockin_period}} {{$savings_transaction->savings->lockin_type}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>{{ trans_choice('core::general.payment',1) }} {{ trans_choice('core::general.detail',2) }}</b>
            </td>
        </tr>
        @if(!empty($savings_transaction->payment_detail))
            <tr>
                <td>{{ trans_choice('core::general.payment',1) }} {{ trans_choice('core::general.type',1) }}</td>
                <td class="text-right">
                    @if(!empty($savings_transaction->payment_detail->payment_type))
                        {{$savings_transaction->payment_detail->payment_type->name}}
                    @endif
                </td>
            </tr>
            @if(!empty($savings_transaction->payment_detail->account_number))
                <tr>
                    <td>{{ trans_choice('core::general.account',1) }}#</td>
                    <td class="text-right">
                        {{$savings_transaction->payment_detail->account_number}}
                    </td>
                </tr>
            @endif
            @if(!empty($savings_transaction->payment_detail->cheque_number))
                <tr>
                    <td>{{ trans_choice('core::general.cheque',1) }}#</td>
                    <td class="text-right">
                        {{$savings_transaction->payment_detail->cheque_number}}
                    </td>
                </tr>
            @endif
            @if(!empty($savings_transaction->payment_detail->routing_code))
                <tr>
                    <td>{{ trans_choice('core::general.routing_code',1) }}</td>
                    <td>
                        {{$savings_transaction->payment_detail->routing_code}}
                    </td>
                </tr>
            @endif
            @if(!empty($savings_transaction->payment_detail->receipt))
                <tr>
                    <td>{{ trans_choice('core::general.receipt',1) }}#</td>
                    <td class="text-right">
                        {{$savings_transaction->payment_detail->receipt}}
                    </td>
                </tr>
            @endif
            @if(!empty($savings_transaction->payment_detail->bank_name))
                <tr>
                    <td>{{ trans_choice('core::general.bank',1) }}#</td>
                    <td class="text-right">
                        {{$savings_transaction->payment_detail->bank_name}}
                    </td>
                </tr>
            @endif
            @if(!empty($savings_transaction->payment_detail->description))
                <tr>
                    <td>{{ trans_choice('core::general.description',1) }}</td>
                    <td class="text-right">
                        {{$savings_transaction->payment_detail->description}}
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
    </table>
</div>

<div class="signature">
    <div style="float: left; width: 40%; height: 60px;align-content: flex-end;">
        {{ trans_choice('client::general.client',1) }}  {{ trans_choice('core::general.signature',1) }}
    </div>
    <div style="float: left; width: 60%;height: 20px;border-bottom: dotted 1px #000000">

    </div>
</div>
<div class="signature" style="clear: both">
    <div style="float: left; width: 40%; height: 60px;display: flex;align-content: flex-end;">
        {{ trans_choice('savings::general.witness',1) }}  {{ trans_choice('core::general.signature',1) }}
    </div>
    <div style="float: left; width: 60%;height: 20px;border-bottom: dotted 1px #000000">

    </div>
</div>
<div class="signature" style="clear: both">
    <div style="float: left; width: 40%; height: 60px;display: flex;align-content: flex-end;">
        {{ trans_choice('savings::general.officer',1) }}  {{ trans_choice('core::general.signature',1) }}
    </div>
    <div style="float: left; width: 60%;height: 20px;border-bottom: dotted 1px #000000">

    </div>
</div>

</body>
<script>
    window.print();
</script>
</html>
