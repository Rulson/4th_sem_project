@extends('layouts.main')
@section('title', 'Payment Summary')
@section('heading', 'Payment Summary')
@section('breadcrumb')
    @parent
    <li>Payment Information</li>
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
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Payment Information</h3>

            </div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="info-box" style="background-color: #f3ecec;">
                                        <span class="info-box-icon bg-aqua"><i class="ion ion-cash"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Distributor Total</span>
                                            <span class="info-box-number">$ {{$distributorPaymentSummary['distributorTotal']}}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="info-box" style="background-color: #f3ecec;">
                                        <span class="info-box-icon bg-red"><i class="ion ion-cash"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Paid For Transaction</span>
                                            <span class="info-box-number">$ {{$distributorPaymentSummary['paidForTransaction']}}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="info-box" style="background-color: #f3ecec;">
                                        <span class="info-box-icon bg-green"><i class="ion ion-cash"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Remaining Balance</span>
                                            <span class="info-box-number">$ {{$distributorPaymentSummary['outstandingBalance']}}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Description</th>
                        <th scope="col">Payment For Transaction (NRs)</th>
                        <th scope="col">Payment To Distributor</th>
                        <th scope="col">Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $data=array_reverse($distributorPaymentSummary['paymentSummary']); @endphp

                    @foreach($data as $key=>$value)
                        <tr>


                            <td scope="row">{{$value['date']}}</td>
                            <td>{{$value['description']}}</td>
                            <td>{{$value['balance']}}</td>
                            <td>{{$value['payment_amount']}}</td>
                            <td>{{$value['t_balance']}}</td>
                        </tr>


                    @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    </section>






@endsection