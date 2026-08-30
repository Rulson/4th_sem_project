@extends('layouts.main')
@section('title', 'Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Distributors</li>
    <li class="breadcrumb-item active">{{getDistributorOfficeName($data->id)}}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="sendmoney-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Distributor Details</strong>
                            <div class="pull-right">
                                @if(Auth::user()->level_id != 6)
                                <a href="{{route('distributor.edit',$data->id)}}"
                                   class="btn btn-success  btn-sm pull-right"><i class="fa fa-pencil">
                                        Edit Distributor</i></a>
                                    @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Distributor Id</dt>
                                        <dd>{{format_id($data->id,"D")}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Company Name</dt>
                                        <dd>{{$data->companyName}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Phone</dt>
                                        <dd>{{$data->phoneNumber}}</dd>


                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Email</dt>
                                        <dd>{{$data->email}}</dd>

                                    </dl>
                                </div>
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Address</dt>
                                        <dd>{{$data->street}} {{$data->suburb}} {{$data->state}} {{$data->postCode}} {{$data->country}}
                                        </dd>
                                    </dl>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-group mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0">AUD {{$distributorPaymentSummary['distributorTotal']}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total
                                                Balance
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0">{{$distributorPaymentSummary['paidForTransaction']}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total Paid
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0 font-lg">AUD {{$distributorPaymentSummary['outstandingBalance']}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Remaining Balance
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#transactions" role="tab"
                                               aria-controls="home"><i class="icon-calculator"></i> Transactions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#paymentsummary" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Payment Summary</a>
                                        </li>
                                        @if(Auth::user()->level_id == 1)
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#paymentlist" role="tab"
                                                   aria-controls="payments"><i class="fa fa-money"></i> Payments</a>
                                            </li>
                                        @endif

                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="transactions" role="tabpanel">

                                            <div class="card" style="overflow-x: scroll;">
                                                <table id="exampleTransaction" class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>

                                                        <th>ID</th>
                                                        <th>Beneficiary Name</th>
                                                        <th>Sending Amount</th>
                                                        <th>Payment Amount</th>
                                                        <th>Rate</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($distributor_transactions as $transaction)
                                                        <tr>
                                                            <td>{{$transaction->id}}</td>
                                                            <td>{{getBeneficiaryName($transaction->beneficiary_id)}}</td>
                                                            <td>{{number_format($transaction->distributor_amount/$transaction->cost_rate,2)}}</td>
                                                            <td>{{$transaction->distributor_amount}}</td>
                                                            <td>{{$transaction->cost_rate}}</td>
                                                            <td>{{$transaction->name}}</td>
                                                            <td>

                                                                <a href="{{route('transactions.show',$transaction->id)}}"
                                                                   data-toggle="tooltip" data-placement="bottom" title="View"
                                                                   class="btn btn-xs btn-success"><i
                                                                            class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>

                                        <div class="tab-pane" id="paymentsummary" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-primary p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-primary mb-0 pt-3">$  {{$distributorPaymentSummary['distributorTotal']}}</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs">Distributor Total</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/.col-->
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-success p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-info mb-0 pt-3">$ {{$distributorPaymentSummary['paidForTransaction']}}</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs"> Paid for Transaction</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/.col-->
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-red p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-warning mb-0 pt-3">$ {{$distributorPaymentSummary['outstandingBalance']}}</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs">Remaining Balance</div>
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
                                                                <th scope="col">Payment for Transaction</th>
                                                                <th scope="col">Payment to distributor</th>
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
                                            </div>

                                        </div>
                                        @if(Auth::user()->level_id == 1)
                                            <div class="tab-pane" id="paymentlist" role="tabpanel">
                                                <div class="card" style="overflow-x: scroll;">
                                                    <table id="exampleTransaction11"
                                                           class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>

                                                            <th>Payment ID</th>
                                                            <th>Date</th>
                                                            <th>Amount</th>
                                                            <th>Cost Rate</th>
                                                            <th>Payment Method</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>

                                                        </thead>
                                                        <tbody>
                                                        @foreach($distributor_payments as $payment)
                                                            <tr>

                                                                <td>{{$payment->id}}</td>
                                                                <td>{{standard_date($payment->date)}}</td>
                                                                <td>{{$payment->amount}}</td>
                                                                <td>{{$payment->cost_rate}}</td>
                                                                <td>{{$payment->method}}</td>
                                                                <td>{{$payment->description}}</td>
                                                                <td>

                                                                    <a href="{{route('distributor.payment.edit',$payment->id)}}"
                                                                       data-toggle="tooltip" data-placement="bottom"
                                                                       title="View"
                                                                       class="btn btn-sm btn-primary"><i
                                                                                class="fa fa-edit"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>


                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <style>
        .due-amount .card-body {
            padding: 10px;
        }
    </style>
    <style>
        .list-group-item {
            cursor: pointer;
        }

        .tt-menu {
            width: 100% !important;
        }

        .typeahead {
            positin: relative;
        }

        .Typeahead-spinner {
            position: absolute;
            right: 93px;
            top: 23px;
            display: none;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

@endsection


