@extends('layouts.main')
@section('title', 'Transactions')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Transaction Tracker</li>
    <li  class="breadcrumb-item active">Confirmed</li>
@stop
@section('content')
    @include('Transaction::tracker/nodes/header')
    @include('Transaction::tracker/nodes/stages')
    @php

        $distributorOffice = \App\Modules\Distributor\Models\DistributorUser::where('user_id', Auth::user()->id)->first();

    @endphp
    <style>

        .label-comment {
            right: -10px;
            line-height: 0.9;
            padding: 2px 3px;
            position: absolute;
            top: -8px;

        }
    </style>
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Confirmed
                <div class="pull-right">
                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                        <button class=" box-title btn btn-primary pull-right" id="excel-export" style="font-size: 15px;">Export</button>
                    @endif
                </div>
            </div>
            @include('flash::message')
        <div class="card-body">
                            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                <table id="confirmedTransaction" class="table table-bordered table-striped ">

                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check-all"/></th>
                                        <th>Trans Id</th>
                                        <th>Date</th>
                                        <th>Sender Name</th>
                                        <th>Beneficiary Name</th>
                                        <th>Total Amount (AUD)</th>
                                        <th>Service Charge (AUD)</th>
                                        <th>Sending Amount(AUD)</th>
                                        <th>Payment Amount (NPR)</th>
                                        <th>Client Rate</th>
                                        <th>Agent Rate</th>
                                        <th>Agent Commission</th>
                                        <th>Added By</th>
                                        <th>Distributor</th>
                                        <th>Staff Note</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>


                                    <tbody class="confirmed-color">
                                    </tbody>
                                    <tfoot class="confirmed-color">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>AUD {{$sum['totalAmount']}}</td>
                                        <td>AUD {{$sum['totalServiceCharge']}}</td>
                                        <td>AUD {{$sum['totalSendingAmount']}}</td>
                                        <td>NPR {{$sum['totalPaymentAmount']}}</td>
                                        <td></td>
                                        <td></td>
                                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) <td>AUD {{$sum['totalAgentCommission']}}</td>@else <td></td>@endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            @elseif(Auth::user()->level_id == 3)
                                <table id="confirmedTransaction" class="table table-bordered table-striped">

                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check-all"/>
                                        </th>
                                        <th>Trans Id</th>
                                        <th>Date</th>
                                        <th>Sender Name</th>
                                        <th>Beneficiary Name</th>
                                        <th>Total Amount (AUD)</th>
                                        <th>Service Charge (AUD)</th>
                                        <th>Sending Amount(AUD)</th>
                                        <th>Payment Amount (NPR)</th>
                                        <th>Client Rate</th>
                                        <th>Agent Rate</th>
                                        <th>Agent Commission</th>
                                        <th>Added By</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>


                                    <tbody class="confirmed-color">

                                    </tbody>
                                    <tfoot class="confirmed-color">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>AUD {{$sum['totalAmount']}}</td>
                                        <td>AUD {{$sum['totalServiceCharge']}}</td>
                                        <td>AUD {{$sum['totalSendingAmount']}}</td>
                                        <td>NPR {{$sum['totalPaymentAmount']}}</td>
                                        <td></td>
                                        <td></td>
                                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) <td>AUD {{$sum['totalAgentCommission']}}</td>@else <td></td>@endif
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    </tfoot>
                                </table>
                            @elseif(Auth::user()->level_id == 5)
                                <table id="confirmedTransaction" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check-all"/>
                                        </th>
                                        <th>Trans Id</th>
                                        <th>Date</th>
                                        <th>Sender Name</th>
                                        <th>Beneficiary Name</th>
                                        <th>Total Amount (AUD)</th>
                                        <th>Service Charge (AUD)</th>
                                        <th>Sending Amount(AUD)</th>
                                        <th>Payment Amount (NPR)</th>
                                        <th>Client Rate</th>
                                        <th>Added By</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="confirmed-color">
                                    </tbody>
                                    <tfoot class="confirmed-color">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>AUD {{$sum['totalAmount']}}</td>
                                        <td>AUD {{$sum['totalServiceCharge']}}</td>
                                        <td>AUD {{$sum['totalSendingAmount']}}</td>
                                        <td>NPR {{$sum['totalPaymentAmount']}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            @endif
        </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="">
                                <span class="btn btn-primary">Convert To</span>

                            </div>
                            <div class="">
                                <select id="statusChangeSelect2" name="status"  class="form-control" style="width: 100%;">
                                    <option value="3">Send For Collection</option>
                                    <option value="4">Payment In Progress</option>
                                </select>
                            </div>
                            <div class="">
                                <button class="btn btn-success" id="changeStatusButton">Apply</button>
                            </div>
                            <!--/.col-->
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

  <div class="modal fade" id="comment-modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('Transaction::modals/addCommentModal',['transaction_id'=>34])
                </div>
            </div>
        </div>
    </div>



@endsection
@section('page-script')
    @include('layouts.common')

    <script type="text/javascript">
               $(document).ready(function () {

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
            $('#confirmedTransaction').dataTable({

                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/transactions/getTransactionsData/2",
                'scrollX': true,
                "columns": [
                    {data:'checkbox',name:'checkbox', orderable: false, searchable: false},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'transactionDate', name: 'transactionDate', searchable: false},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'beneficiary_name', name: 'beneficiary_name'},
                    {data: 'totalAmount', name: 'totalAmount'},
                    {data: 'serviceCharge', name: 'serviceCharge'},
                    {data: 'sendingAmount', name: 'sendingAmount'},
                    {data: 'paymentAmount', name: 'paymentAmount'},
                    {data: 'exchangeRate', name: 'exchangeRate'},
                    {data: 'agentRate', name: 'agentRate'},
                    {data: 'agentCommission', name: 'agentCommission'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'companyName', name: 'companyName'},
                    {data: 'staff_notes', name: 'staff_notes', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']]
            })

            @elseif(Auth::user()->level_id == 3)
            $('#confirmedTransaction').dataTable({

                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/transactions/getTransactionsData/2",
                'scrollX': true,
                "columns": [
                    {data:'checkbox',name:'checkbox', orderable: false, searchable: false},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'transactionDate', name: 'transactionDate', searchable: false},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'beneficiary_name', name: 'beneficiary_name'},
                    {data: 'totalAmount', name: 'totalAmount'},
                    {data: 'serviceCharge', name: 'serviceCharge'},
                    {data: 'sendingAmount', name: 'sendingAmount'},
                    {data: 'paymentAmount', name: 'paymentAmount'},
                    {data: 'exchangeRate', name: 'exchangeRate'},
                    {data: 'agentRate', name: 'agentRate'},
                    {data: 'agentCommission', name: 'agentCommission'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']]
            })
            ;
            @elseif(Auth::user()->level_id == 5)
            $('#confirmedTransaction').dataTable({

                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/transactions/getTransactionsData/2",
                'scrollX': true,
                "columns": [
                    {data:'checkbox',name:'checkbox', orderable: false, searchable: false},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'transactionDate', name: 'transactionDate', searchable: false},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'beneficiary_name', name: 'beneficiary_name'},
                    {data: 'totalAmount', name: 'totalAmount'},
                    {data: 'serviceCharge', name: 'serviceCharge'},
                    {data: 'sendingAmount', name: 'sendingAmount'},
                    {data: 'paymentAmount', name: 'paymentAmount'},
                    {data: 'exchangeRate', name: 'exchangeRate'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']]
            })
            ;
            @endif
        });
    </script>
@stop









