@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <style>
        .callout ul li{
            display: inline-block;
            padding: 0 10px;
        }
        .callout{
            padding: 0px!important;
            padding-bottom:5px!important;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>User Dashboard</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="callout bg-blue">
            <ul>
                <li>Daily Update</li>
                <li class="arrow-right" style="color:#FFF;">></li>
                <li>Exchange Rate <span> AUD 1.00 = NPR 80<a href="#" data-toggle="modal"
                                                            data-target="#exchange-rate-modal" class="btn btn-blue"
                                                            title="update Exchange Rate"><i class="fa fa-edit"></i></a></span>
                </li>
                <li>Total Transactions: <span>0</span></li>
                <li>Total Received Amount: <span>AUD 0</span></li>
                <li>Total Payment Amount: <span>NPR 0</span></li>
                <li>Total Service Fee: <span>AUD 0</span></li>
                <li>Average Rate: <span> 0 </span></li>

            </ul>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-number" style="text-align: center;">5</span>
                        <span class="info-box-text" style="white-space: pre-wrap; text-align: center;">Outbound Unconfirmed transactions</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>

            </div>

            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-blue" style="width: 84px;  height: 90px; font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>15</h5>
                        <p>Agent</p>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-blue" style="width: 84px;  height: 90px; font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>0</h5>
                        <p>Client</p>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-blue" style="width: 84px;  height: 90px; font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>4</h5>
                        <p>Staff</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-number" style="text-align: center;">0</span>
                        <span class="info-box-text" style="white-space: pre-wrap; text-align: center;">Outbound Pending Transactions</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-green" style="width: 84px;  height: 90px; font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>15</h5>
                        <p>Agent</p>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-green" style="width: 84px;  height: 90px;  font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>0</h5>
                        <p>Client</p>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-xs-6" style="margin-right: 5px;">
                <!-- small box -->
                <div class="small-box bg-green" style="width: 84px;  height: 90px; font-size: 12px;">
                    <div class="inner" style="text-align: center;">
                        <h5>4</h5>
                        <p>Staff</p>
                    </div>
                </div>
            </div>

        </div>
 <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text" style="white-space: pre-wrap;">Total Commission</span>
                        <span class="info-box-number">1234</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
     <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="info-box">
             <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>

             <div class="info-box-content">
                 <span class="info-box-text" style="white-space: pre-wrap;">Total Paid Amount</span>
                 <span class="info-box-number">1234</span>
             </div>
             <!-- /.info-box-content -->
         </div>
     </div>
     <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="info-box">
             <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

             <div class="info-box-content">
                 <span class="info-box-text" style="white-space: pre-wrap;">Due Amount</span>
                 <span class="info-box-number">1234</span>
             </div>
             <!-- /.info-box-content -->
         </div>
     </div>



 </div>
<div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text" style="white-space: pre-wrap;">Distributor Total</span>
                        <span class="info-box-number">1234</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
     <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="info-box">
             <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>

             <div class="info-box-content">
                 <span class="info-box-text" style="white-space: pre-wrap;">Paid For Transaction</span>
                 <span class="info-box-number">1234</span>
             </div>
             <!-- /.info-box-content -->
         </div>
     </div>
     <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="info-box">
             <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

             <div class="info-box-content">
                 <span class="info-box-text" style="white-space: pre-wrap;">Outstanding Balance</span>
                 <span class="info-box-number">1234</span>
             </div>
             <!-- /.info-box-content -->
         </div>
     </div>



 </div>

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Transactions</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                            title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body" style="overflow-x: scroll;">
                <table id="recentTransaction" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Trans Id</th>
                        <th>Date</th>
                        <th style="white-space: nowrap;">Sender Name</th>
                        <th>Beneficiary Name</th>
                        <th>Total Amount</th>
                        <th>Service Charge</th>
                        <th>Sending Amount</th>
                        <th>Payment Amount</th>
                        <th>Client Rate</th>
                        <th>Agent Rate</th>
                        <th>Agent Commission</th>
                        <th>Added By</th>
                        <th>Distributor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                Footer
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@stop
@section('page-script')
    <script>
        $('#recentTransaction').DataTable({
            language: {
                zeroRecords: "No records found",
                "scrollX": true,
            }
        });
    </script>

@stop
