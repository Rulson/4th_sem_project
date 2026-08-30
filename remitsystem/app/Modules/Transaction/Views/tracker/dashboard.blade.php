@extends('layouts.main')
@section('title', 'Transaction Dashboard')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Transaction Manager</li>
    <li  class="breadcrumb-item active">Dashboard</li>
@stop
@section('content')

        <section class="content">
            <div class="row">
            <div class="col-sm-12 col-lg-3">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                       Transactions
                        <div class="pull-right">
                            @if(!in_array(Auth::user()->level_id, [6,7]))<a href="{{ url('send-money') }}" class="btn btn-info btn-sm">Add Transaction</a>@endif
                        </div>
                    </div>
                    <div class="card-body py-3 px-4">
                        <div class="row">
                            <div class="col-12">
                                <strong>{{$total_transaction_count['today']}}<span class="pull-right"> AUD {{number_format($today_admin_profit['today'],2)}}</span></strong>
                                <br>
                                <span class="text-muted pull-left">
                        Today Transaction
                      </span>
                                <span class="text-muted pull-right">Today Profit</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 px-4 b-t-1">
                        <div class="row">
                            <div class="col-6 b-r-1 py-3">
                                <div class="font-weight-bold">{{$total_transaction_count['this_week']}}</div>
                                <div class="text-muted">
                                    <small>This Week</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{$total_transaction_count['this_month']}}</div>
                                <div class="text-muted">
                                    <small>This Month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-sm-12 col-lg-3">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        Sending Amount (AUD)

                    </div>
                    <div class="card-body py-3 px-4">
                        <div class="row">
                            <div class="col-12">
                                <strong>{{$total_transaction_count['today_sending_amount']}}</strong>
                                <br>
                                <span class="text-muted">
                        Total Collected Today
                      </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 px-4 b-t-1">
                        <div class="row">
                            <div class="col-6 b-r-1 py-3">
                                <div class="font-weight-bold">{{$total_transaction_count['this_week_sending_amount']}}</div>
                                <div class="text-muted">
                                    <small>This Week</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{$total_transaction_count['this_month_sending_amount']}}</div>
                                <div class="text-muted">
                                    <small>This Month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="card">
                        <div class="card-header text-white bg-primary">
                            Payment Amount (NPR)
                        </div>
                        <div class="card-body py-3 px-4">
                            <div class="row">
                                <div class="col-12">
                                    <strong>{{$total_transaction_count['today_payment_amount']}}</strong>
                                    <br>
                                    <span class="text-muted">
                       Total Payment Today
                      </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-0 px-4 b-t-1">
                            <div class="row">
                                <div class="col-6 b-r-1 py-3">
                                    <div class="font-weight-bold">{{$total_transaction_count['this_week_payment_amount']}}</div>
                                    <div class="text-muted">
                                        <small>This Week</small>
                                    </div>
                                </div>
                                <div class="col-6 py-3">
                                    <div class="font-weight-bold">{{$total_transaction_count['this_month_payment_amount']}}</div>
                                    <div class="text-muted">
                                        <small>This Month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        AVG Exchange Rate
                    </div>
                    <div class="card-body py-3 px-4">
                        <div class="row">
                            <div class="col-12">
                                <strong>{{number_format($total_transaction_count['today_average_exchange_rate'],2)}}</strong>
                                <br>
                                <span class="text-muted">
                        Today Avg Rate
                      </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 px-4 b-t-1">
                        <div class="row">
                            <div class="col-6 b-r-1 py-3">
                                <div class="font-weight-bold">{{number_format($agent_average_rate,2)}}</div>
                                <div class="text-muted">
                                    <small>Agent Avg Rate</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{number_format($client_average_rate,2)}}</div>
                                <div class="text-muted">
                                    <small>Client Avg Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                         Traffic Overview
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Transactions</span>
                                    <select id="data-as-selected" class="pull-right" style="margin-left:10px;">
                                        <option value="1" selected="selected">Monthly</option>
                                         <option value="2">Weekly</option>
                                        <option value="0" >Yearly</option>

                                    </select>
                                    <label class="pull-right">Select: </label>


                                    <canvas id="transaction" width="400" height="300"></canvas>


                                </div>
                                @if(!in_array(Auth::user()->level_id,[7]))
                                <div class="col-md-6">
                                    <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Transaction By Users Level (AUD)</span>

                                    <select id="data-as-selected-user" class="pull-right" style="margin-left:10px;">
                                        <option value="1" selected="selected">Daily</option>
                                        <option value="2">Weekly</option>
                                        <option value="3" >Monthly</option>
                                        <option value="4" >Yearly</option>
                                        <option value="5" >All Time</option>

                                    </select>
                                    <label class="pull-right">Select: </label>

                                    <canvas id="transactionDoughNut" width="400" height="300"></canvas>
                                </div>
                            </div>
                            <br>
                            <div class="row">

                                <div class="col-md-6">
                                    <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Transaction By Distributors (NPR)</span>
                                    <canvas id="transactionDistributor" width="400" height="300"></canvas>
                                </div>
                            @endif
                                <div class="col-md-6">
                                    <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Today Transaction By Agents (AUD)</span>
                                    <canvas id="transactionAgent" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        function addData(chart, label, data) {
            chart.data.labels=label;
            chart.data.datasets[0].data = data
            chart.update();
        }
        Chart.helpers.merge(Chart.defaults.global, {
            tooltips: true,
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
                enabled: true
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


        var ctxtransaction = document.getElementById('transaction').getContext('2d');
        var transaction = new Chart(ctxtransaction, {
            "type":"line",
            "data":{
                "labels":['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug','Sep','oct','nov','dec'],
                "datasets":[{
                    "label":"Sending Amount(AUD)",
                    "data":[0,0,0,0,0,0,0,0],
                    backgroundColor: '#ff7f9b',
                    borderColor: '#ff7f9b',
                    "fill":false,
                    // "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1,
                },{
                    "label":"Payment Amount(NPR)",
                    "data":[0,0,0,0,0,0,0,0,0],
                    backgroundColor: '#5db4ee',
                          borderColor: '#5db4ee',
                    "fill":false,
                    // "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1,
                },{
                    "label":"No. of Transactions",
                    "data":[0,0,0,0,0,0,0,0,0,0],
                    backgroundColor: '#17ee67',
                    borderColor: '#1cee97',
                    "fill":false,
                    // "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1,
                }]},
            "options":{

                scaleShowLabels : false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            /*precision:0*/

                        }
                    }]
                }
            }});

        /*piechart*/
        var ctxTransactionDoughNut = document.getElementById("transactionDoughNut").getContext('2d');
        var transactionDoughNut = new Chart(ctxTransactionDoughNut, {
            type: 'doughnut',

            data: {
                labels: [],
                datasets: [{
                    label: '# of Votes',
                    data: [0,0,0,0,0,0,0,0,0],
                    // backgroundColor: [
                    //     'rgba(255, 99, 132, 0.2)',
                    //     'rgba(54, 162, 235, 0.2)',
                    //     'rgba(255, 206, 86, 0.2)',
                    //     'rgba(75, 192, 192, 0.2)',
                    //     'rgba(153, 102, 255, 0.2)',
                    //     'rgba(255, 159, 64, 0.2)'
                    // ],
                    // borderColor: [
                    //     'rgba(255,99,132,1)',
                    //     'rgba(54, 162, 235, 1)',
                    //     'rgba(255, 206, 86, 1)',
                    //     'rgba(75, 192, 192, 1)',
                    //     'rgba(153, 102, 255, 1)',
                    //     'rgba(255, 159, 64, 1)'
                    // ],
                    backgroundColor: [
                        "#506eff",
                        "#64ebdf",
                        "#adffae",
                        "#00FA9A",
                        "#8A2BE2",
                        "#8FBC8F",
                        "#DE7B40",
                        "#B5E6EE",
                        "#3DA9D0",
                        "#9bf442",
                        "#f4c137",
                        "#25f48a",
                        "#f41522",
                        "#11cff4",
                        "#11f441",
                        "#f48412",
                        "#cb25f4",
                        "#f43377",
                        "#f44904",
                        "#5608f4",
                        "#bfbcf4",
                        "#67cbf4",
                        "#a0f412",
                        "#06aaf4",
                        "#f42333",
                        "#f422a0",

                    ],
                 /*   hoverBackgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#FF6384"
                    ],
                 */   borderWidth: 2
                }]
            },
            labels: [
                'Red',
                'Yellow',
                'Blue'
            ],
            options: {
                legend: {
                    display: true,
                    labels: {

                    }
                }
            }
        });
        var ctxTransactionDistributor = document.getElementById("transactionDistributor").getContext('2d');
        var transactionDistributor = new Chart(ctxTransactionDistributor, {
            type: 'doughnut',

            data: {
                labels: [],
                datasets: [{
                    label: '# of Votes',
                    data: [0,0,0,0,0,0,0],
                    // backgroundColor: [
                    //     'rgba(255, 99, 132, 0.2)',
                    //     'rgba(54, 162, 235, 0.2)',
                    //     'rgba(255, 206, 86, 0.2)',
                    //     'rgba(75, 192, 192, 0.2)',
                    //     'rgba(153, 102, 255, 0.2)',
                    //     'rgba(255, 159, 64, 0.2)'
                    // ],
                    // borderColor: [
                    //     'rgba(255,99,132,1)',
                    //     'rgba(54, 162, 235, 1)',
                    //     'rgba(255, 206, 86, 1)',
                    //     'rgba(75, 192, 192, 1)',
                    //     'rgba(153, 102, 255, 1)',
                    //     'rgba(255, 159, 64, 1)'
                    // ],
                    backgroundColor: [
                        "#506eff",
                        "#11f441",
                        "#9bf442",
                        "#00FA9A",
                        "#8A2BE2",
                        "#8FBC8F",
                        "#DE7B40",
                        "#B5E6EE",
                        "#3DA9D0",
                        "#adffae",
                        "#f4c137",
                        "#25f48a",
                        "#f41522",
                        "#11cff4",
                        "#64ebdf",


                     /*   "#f48412",
                        "#cb25f4",
                        "#f43377",
                        "#f44904",
                        "#5608f4",
                        "#bfbcf4",
                        "#67cbf4",
                        "#a0f412",
                        "#06aaf4",
                        "#f42333",
                        "#f422a0",
*/
                    ],
/*
                    hoverBackgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#FF6384"
                    ],
*/
                    borderWidth: 2
                }]
            },
            labels: [
                'Red',
                'Yellow',
                'Blue'
            ],
            options: {
                // title: {
                //     text: 'Follow up by Category'
                // }
            }
        });
        var ctxTransactionAgent = document.getElementById("transactionAgent").getContext('2d');
        var transactionAgent = new Chart(ctxTransactionAgent, {
            type: 'doughnut',

            data: {
                labels: [],
                datasets: [{
                    label: '# of Votes',
                    data: [0,0,0,0,0,0,0,0,0],
                    // backgroundColor: [
                    //     'rgba(255, 99, 132, 0.2)',
                    //     'rgba(54, 162, 235, 0.2)',
                    //     'rgba(255, 206, 86, 0.2)',
                    //     'rgba(75, 192, 192, 0.2)',
                    //     'rgba(153, 102, 255, 0.2)',
                    //     'rgba(255, 159, 64, 0.2)'
                    // ],
                    // borderColor: [
                    //     'rgba(255,99,132,1)',
                    //     'rgba(54, 162, 235, 1)',
                    //     'rgba(255, 206, 86, 1)',
                    //     'rgba(75, 192, 192, 1)',
                    //     'rgba(153, 102, 255, 1)',
                    //     'rgba(255, 159, 64, 1)'
                    // ],
                    backgroundColor: [
                        "#506eff",
                        "#64ebdf",
                        "#adffae",
                        "#00FA9A",
                        "#8A2BE2",
                        "#8FBC8F",
                        "#DE7B40",
                        "#B5E6EE",
                        "#3DA9D0",
                        "#9bf442",
                        "#f4c137",
                        "#25f48a",
                        "#f41522",
                        "#11cff4",
                        "#11f441",
                        "#f48412",
                        "#cb25f4",
                        "#f43377",
                        "#f44904",
                        "#5608f4",
                        "#bfbcf4",
                        "#67cbf4",
                        "#a0f412",
                        "#06aaf4",
                        "#f42333",
                        "#f422a0",
                        "#35f407",
                        "#e2f423",
                        "#f4661f",
                        "#6fb1f4",
                        "#f4d679",
                        "#b89af4",
                        "#e5c2f4",
                        "#8845f4",
                        "#2cc2f4",
                        "#f4df26",
                        "#8cf48e",
                        "#f4d4d4",
                        "#ebf446",
                        "#f4cc51",
                        "#0cf42a",
                        "#073ff4",
                        "#f41128",
                        "#e56cf4",
                        "#90dff4",
                        "#c208f4",
                        "#f43be5",
                        "#f433a4",
                        "#f4205e",
                        "#f45810",
                        "#cbf41b",
                    ],
/*
                    hoverBackgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#FF6384"
                    ],
*/
                    borderWidth: 2
                }]
            },
            labels: [
                'Red',
                'Yellow',
                'Blue'
            ],
            options: {
                // title: {
                //     text: 'Follow up by Category'
                // }
            }
        });
    </script>

@endsection
@section('page-script')
    @include('layouts.common')
    @stop








