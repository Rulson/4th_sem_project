@extends('layouts.main')
@section('title', 'Payment Summary')
@section('breadcrumb')
    @parent

    <li  class="breadcrumb-item active">Payment Summary</li>
@stop
@section('content')
    <style>
        .bg-blue-dark {
            background: #2c3b41;
            color: #fff;
        }

        .no-box-shadow {
            box-shadow: none
        }

        .b-0 {
            border: 0;
        }

        .m-0 {
            margin: 0
        }

        .vr-bar {
            position: relative;
        }

        .vr-bar:after {
            content: '';
            position: absolute;
            background: white;
            display: block;
            top: 6px;
            right: 0px;
            bottom: 6px;
            width: 2px;
        }

        .table.recent-transactions > tbody > tr > td, .table.recent-transactions > tbody > tr > th {
            vertical-align: middle;
            text-align: center;
        }

        .table.recent-transactions > tbody > tr > td:first-child, .table.recent-transactions > tbody > tr > th:first-child {
            text-align: left;
        }

        .table.recent-transactions > tbody > tr > td:last-child, .table.recent-transactions > tbody > tr > th:last-child {
            text-align: right;
        }
    </style>
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
            Payment Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="card">
                            <div class="card-body p-0 clearfix">
                                <i class="fa fa-money bg-primary p-4 px-5 font-2xl mr-3 float-left"></i>
                                <div class="h5 text-primary mb-0 pt-3">$ {{$agentPaymentSummary['totalcommission']}}</div>
                                <div class="text-muted text-uppercase font-weight-bold font-xs">Total Commission</div>
                            </div>
                        </div>
                    </div>
                    <!--/.col-->
                    <div class="col-sm-12 col-lg-4">
                        <div class="card">
                            <div class="card-body p-0 clearfix">
                                <i class="fa fa-money bg-success p-4 px-5 font-2xl mr-3 float-left"></i>
                                <div class="h5 text-info mb-0 pt-3">${{$agentPaymentSummary['totalpayment']}}</div>
                                <div class="text-muted text-uppercase font-weight-bold font-xs">Total Paid Amount</div>
                            </div>
                        </div>
                    </div>
                    <!--/.col-->
                    <div class="col-sm-12 col-lg-4">
                        <div class="card">
                            <div class="card-body p-0 clearfix">
                                <i class="fa fa-money bg-red p-4 px-5 font-2xl mr-3 float-left"></i>
                                <div class="h5 text-warning mb-0 pt-3">$ @if($agentPaymentSummary['dueamount'] == -0)0 @else{{$agentPaymentSummary['dueamount']}}@endif</div>
                                <div class="text-muted text-uppercase font-weight-bold font-xs">Due Amount</div>
                            </div>
                        </div>
                    </div>
                </div>
                           <div class="">
                    <table class="table table-bordered">
                        <thead class="">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Description</th>
                            <th scope="col">Commission Amount</th>
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $data=array_reverse($agentPaymentSummary['paymentSummary']); @endphp
                        @foreach($data as $key=>$value)
                            <tr>
                                <td scope="row">{{$value['date']}}</td>
                                <td>{{$value['description']}}</td>
                                <td>{{$value['commission']}}</td>
                                <td>{{$value['payment_amount']}}</td>
                                @if($value['balance'] == -0)
                                    <td>0</td>
                                @else
                                    <td>{{$value['balance']}}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>










@endsection