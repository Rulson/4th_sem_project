@extends('adminPanel.layouts.master')
@section('page-title')
    Agents
@endsection
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

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Agents
                <small>view</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Agents</a></li>
                <li class="active">Agents Name</li>
            </ol>
        </section>

        <section class="content">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#sender-overview" data-toggle="tab">Agent Overview</a></li>
                    <li><a href="#transactions" data-toggle="tab">Transactions</a></li>
                    <li><a href="#sendersAdded" data-toggle="tab">Senders Added</a></li>
                    <li><a href="#paymentSummary" data-toggle="tab">Payment Summary</a></li>
                </ul>
                <div class="tab-content" style="padding: 15px">
                    <div class="row">
                        <div class="col-xs-12  ">
                            <div class="row">
                                <div class="info-box bg-blue-dark clearfix">
                                    <div class="col-xs-3 vr-bar">
                                        <div class="info-box bg-blue-dark no-box-shadow ">
                                            <div class="info-box-content" style="margin-left: 0">
                                                <ul class="list-group" style="margin: 0; border: 0">
                                                    <li class="list-group-item bg-blue-dark b-0">
                                                        <b>Agent ID: </b> <span class="pull-right">C080909</span>
                                                    </li>
                                                    <li class="list-group-item bg-blue-dark b-0">
                                                        <b>Created At: </b> <span class="pull-right">12-MAR-2019</span>
                                                    </li>
                                                    <li class="list-group-item bg-blue-dark b-0">
                                                        <b>Created By: </b> <span class="pull-right">Mark Angel</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="info-box bg-blue-dark no-box-shadow">

                                            <div class="info-box-content" style="margin-left: 0">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span class="info-box-number">Name</span>
                                                        <span class="info-box-text">John Doe</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="info-box-number">Phone</span>
                                                        <span class="info-box-text">89898989898</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="info-box-number">Email</span>
                                                        <span class="info-box-text">johndoe@gmail.com</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="info-box-number">Address</span>
                                                        <span class="info-box-text">23 Exets street SW 3s
                                                        Australia</span>

                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">

                            <!-- /.box-body -->
                        </div>
                    </div>

                    <div class="active tab-pane" id="sender-overview">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- About Me Box -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Commission Summary</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <ul class="list-group list-group-unbordered m-0">
                                            <li class="list-group-item b-0">
                                                <b>Total Commission:</b> <span class="pull-right">1000</span>
                                            </li>
                                            <li class="list-group-item b-0">
                                                <b>Total Paid:</b> <span class="pull-right">500</span>
                                            </li>
                                            <li class="list-group-item b-0">
                                                <b>Outstanding Commission</b> <span class="pull-right">500</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <div class="box box-primary">
                                    <div class="box-header with-border">



                                            <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Transaction Timeline</span>

                                            <canvas id="agent" width="400" height="500"></canvas>



                                    </div>
                                    <div class="chart">
                                        <canvas id="barChart" style="height:230px"></canvas>
                                    </div>

                                </div>
                                <!-- /.box -->

                                  </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="box box-widget widget-user">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-green">
                                                <h3 class="widget-user-username">Total Transaction</h3>
                                                <span class="info-box-number">20</span>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="box box-widget widget-user">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-yellow">
                                                <h3 class="widget-user-username">Total Beneficiaries</h3>
                                                <span class="info-box-number">5</span>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="box box-widget widget-user">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-aqua">
                                                <h3 class="widget-user-username">Total Amount Sent</h3>
                                                <span class="info-box-number">AUD 2000 | NPR 5000</span>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="box">
                                            <div class="box-header">
                                                <h3 class="box-title">Recent Transactions</h3>

                                                <div class="box-tools">
                                                    <div class="input-group input-group-sm" style="width: 150px;">
                                                        <input type="text" name="table_search"
                                                               class="form-control pull-right"
                                                               placeholder="Search">

                                                        <div class="input-group-btn">
                                                            <button type="submit" class="btn btn-default"><i
                                                                        class="fa fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive no-padding">
                                                <table class="table table-hover recent-transactions">
                                                    <tbody>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Beneficiary Name</th>
                                                        <th>Sending Amount</th>
                                                        <th>Payment Amount</th>
                                                        <th>Rate</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="user-panel">
                                                                <div class="image">
                                                                    <img src="../../dist/img/user2-160x160.jpg"
                                                                         class="img-circle" alt="User Image">
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>John Doe</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td><span class="label label-success">Approved</span></td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="user-panel">
                                                                <div class="image">
                                                                    <img src="../../dist/img/user2-160x160.jpg"
                                                                         class="img-circle" alt="User Image">
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>Alexander Pierce</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td><span class="label label-warning">Pending</span></td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="user-panel">
                                                                <div class="image">
                                                                    <img src="../../dist/img/user2-160x160.jpg"
                                                                         class="img-circle" alt="User Image">
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>Bob Doe</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td><span class="label label-primary">Approved</span></td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="user-panel">
                                                                <div class="image">
                                                                    <img src="../../dist/img/user2-160x160.jpg"
                                                                         class="img-circle" alt="User Image">
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>Mike Doe</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td>45</td>
                                                        <td><span class="label label-danger">Denied</span></td>

                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer clearfix">
                                                <ul class="pagination pagination-sm no-margin pull-right">
                                                    <li><a href="#">«</a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">»</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- /.box -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="transactions">
                        Transactions
                    </div>
                    <div class="tab-pane" id="sendersAdded">
                        Senders Added List
                    </div>
                    <div class="tab-pane" id="paymentSummary">
                        Payment Summary List
                    </div>
                </div>
                <!-- /.tab-pane -->
                <!-- /.tab-content -->
            </div>
        </section>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        Chart.helpers.merge(Chart.defaults.global, {
            tooltips: false,
            layout: {
                padding: {
                    top: 5
                }
            },
            plugins: {
                datalabels: {
                    arc: true,
                    align: 'center',
                    font: function(context) {
                        var w = context.chart.width;
                        return {
                            size: w < 512 ? 12 : 14
                        }
                    },
                    formatter: function(value, context) {
                        return context.dataset.data[context.dataIndex];
                    },
                    precision: 2,
                    color: '#fff',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            tooltips: {
                enabled: false
            },
            responsive: true,
            legend: {
                position: 'bottom',
            },
            title: {
                fontStyle: 'normal',
                fontColor: '#000',
                fontFamily: "'Source Sans Pro',sans-serif",
                display: true,
                fontSize: 18,
                lineHeight: 1,
                position: 'top',
                text: ''
            }
        });

        var ctxAgent = document.getElementById('agent').getContext('2d');
        var agent = new Chart(ctxAgent, {
            type: 'horizontalBar',
            data: {
                labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug','Sep','oct','nov','dec'],
                datasets: [{
                    label: 'Transaction Number',
                    backgroundColor: '#ff7f9b',
                    borderColor: '#ff7f9b',
                    borderWidth: 1,
                    data: [10,0,10,7,8,7,8]
                }],
                options: {
                    // title: {
                    //     text: 'Lead Conversions'
                    // },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 6,
                                max: 60 //max value for the chart is 60
                            }

                        }]
                    }
                }}})
    </script>
@endsection








