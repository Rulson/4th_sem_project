@extends('layouts.main')
@section('title', 'Agent Dashboard')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Agent</li>
    <li  class="breadcrumb-item active">Agent Dashboard</li>
@stop

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        Transactions
                        <div class="pull-right">
                           @if(!in_array(Auth::user()->level_id ,[6,7,8])) <a href="{{ url('send-money') }}" class="btn btn-info btn-sm">Add Transaction</a>@endif
                        </div>
                    </div>
                    <div class="card-body py-3 px-4">
                        <div class="row">
                            <div class="col-12">
                                <strong>{{$agent_total_transaction_count['today']}}</strong>
                                <br>
                                <span class="text-muted">
                        Today Transaction
                      </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 px-4 b-t-1">
                        <div class="row">
                            <div class="col-6 b-r-1 py-3">
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_week']}}</div>
                                <div class="text-muted">
                                    <small>This Week</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_month']}}</div>
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
                                <strong>{{$agent_total_transaction_count['today_sending_amount']}}</strong>
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
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_week_sending_amount']}}</div>
                                <div class="text-muted">
                                    <small>This Week</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_month_sending_amount']}}</div>
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
                                <strong>{{$agent_total_transaction_count['today_payment_amount']}}</strong>
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
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_week_payment_amount']}}</div>
                                <div class="text-muted">
                                    <small>This Week</small>
                                </div>
                            </div>
                            <div class="col-6 py-3">
                                <div class="font-weight-bold">{{$agent_total_transaction_count['this_month_payment_amount']}}</div>
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
                                <strong>{{number_format($agent_total_transaction_count['today_average_exchange_rate'],2)}}</strong>
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
                                <div class="font-weight-bold">{{number_format($client_average_rate_for_agent,2)}}</div>
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

                                <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Customer Transactions</span>
                                <select id="data-as-selected1" class="pull-right" style="margin-left:10px;">
                                    <option value="1" selected="selected">Monthly</option>
                                    <option value="2">Weekly</option>
                                    <option value="0" >Yearly</option>

                                </select>
                                <label class="pull-right">Select: </label>


                                <canvas id="transactionAgentDashboard" width="400" height="300"></canvas>


                            </div>

                            <div class="col-md-6">
                                <span style="font-size: 18px;line-height: 1;font-style: normal;color: #000">Today Transaction By Customers (AUD)</span>
                                <canvas id="transactionAgent" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Customer Details
                    </div>
                    <div class="card-body">

                        <table id="agentDashboard" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Customer Id</th>
                                <th>Customer Name</th>
                                <th>Total Commission</th>
                                <th>Pending Commission</th>
                                <th>Total Transaction</th>
                                <th>Total Sent (AUD)</th>
                                <th>This Month (AUD)</th>
                                <th>This Week (AUD)</th>
                                <th>Today (AUD)</th>
                                <th>Action</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach($agents as $agent)
                            <tr>
                                <td>{{format_id($agent->id,'A')}}</td>
                                <td><a href="{{route('agent.show',$agent->id)}}">{{getAgentName($agent->id)}}</a></td>
                                <td>{{getAgentCommission($agent->id)}}</td>
                                <td>{{round((getAgentCommission($agent->id)-getAgentPayment($agent->id)),2)}}</td>
                                <td>{{getIndividualAgentTransaction($agent->id)['total_count']}}</td>
                                <td>{{getIndividualAgentTransaction($agent->id)['total_sent']}}</td>
                                <td>{{getIndividualAgentTransaction($agent->id)['this_month']}}({{getIndividualAgentTransaction($agent->id)['this_month_transaction_count']}})</td>
                                <td>{{getIndividualAgentTransaction($agent->id)['this_week']}}({{getIndividualAgentTransaction($agent->id)['this_week_transaction_count']}})</td>
                                <td>{{getIndividualAgentTransaction($agent->id)['today']}}({{getIndividualAgentTransaction($agent->id)['today_transaction_count']}})</td>
                                <td><a href="{{route('agent.show',$agent->id)}}" data-toggle="tooltip" data-placement="bottom" title="View"
                                       class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                    @if(!in_array(Auth::user()->level_id, [2,6,7,8]))<a href="{{route('agent.edit',$agent->id)}}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                       class="btn btn-sm btn-primary"><i
                                                class="fa fa-edit"></i></a>@endif


                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>

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


        var ctxtransactionAgentDashboard = document.getElementById('transactionAgentDashboard').getContext('2d');
        var transactionAgentDashboard = new Chart(ctxtransactionAgentDashboard, {
            "type":"line",
            "data":{
                "labels":['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July','Aug','Sep','oct','nov','dec'],
                "datasets":[{
                    "label":"Sending Amount(AUD)",
                    "data":[0,0,0,0,0,0,0],
                    backgroundColor: '#ff7f9b',
                    borderColor: '#ff7f9b',
                    "fill":false,
                    "lineTension":0.1,
                },{
                    "label":"Payment Amount(NPR)",
                    "data":[0,0,0,0,0,0,0],
                    backgroundColor: '#5db4ee',
                    borderColor: '#5db4ee',
                    "fill":false,
                    "lineTension":0.1,
                }]},
            "options":{

                scaleShowLabels : false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                }
            }});

        /*piechart*/
        var ctxTransactionAgent = document.getElementById("transactionAgent").getContext('2d');
        var transactionAgent = new Chart(ctxTransactionAgent, {
            type: 'doughnut',

            data: {
                labels: [],
                datasets: [{
                    label: '# of Votes',
                    data: [0,0,0,0],
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
                    borderWidth: 2
                }]
            },
            labels: [
                'Red',
                'Yellow',
                'Blue'
            ],
            options: {
            }
        });
    </script>
@endsection

@section('page-script')
    @include('layouts.common')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#agentDashboard').DataTable({
                'scrollX': true,
                order: [[0, 'desc']],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    'copy', 'csv', 'excel', 'print'
                ],
                "pageLength": 50,
            });
        });
    </script>
@stop








