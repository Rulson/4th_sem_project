@extends('layouts.main')
@section('title', 'Agents')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Agents</li>
    <li class="breadcrumb-item active">{{$agent->first_name}} {{$agent->last_name}}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="money-transfer-modal" tabindex="-1" role="dialog"
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
                            <strong>Agent Details</strong>
                            @if(!in_array(Auth::user()->level_id, [2,6,7,8]))
                            <div class="pull-right">
                                <a href="{{route('agent.edit',$agent->agent_id)}}"
                                   class="btn btn-success  btn-sm pull-right"><i class="fa fa-pencil">
                                        Edit Agent</i></a>
                                <a href="{{route('agent.payment.create.individual',$agent->agent_id)}}"
                                   class="btn btn-primary btn-sm pull-right">Add Payment </a>

                            </div>
                                @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1">
                                    <dl>
                                        <dt>Agent Id</dt>
                                        <dd>{{format_id($agent->agent_id)}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Agent Name</dt>
                                        <dd>{{$agent->first_name}} {{$agent->last_name}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Phone</dt>
                                        <dd>{{$agent->number}}</dd>


                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Email</dt>
                                        <dd>{{$agent->email}}</dd>

                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Service Charge</dt>
                                        <dd>{{$agent->agent_service_charge}}</dd>

                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>Address</dt>
                                        <dd>{{$agent->street}} {{$agent->suburb}} {{$agent->state}} {{$agent->postcode}} {{$agent->country}}
                                        </dd>
                                    </dl>
                                </div>
                                <div class="row ml-2">
                                    <div class="pb-3 ">
                                        Shareable Link: <input type="text" style="display: none" readonly
                                                               value="{{_generateAgentShareableLink($agent->agent_id)}}" id="linkInput">
                                        <button type="button" class="btn btn-secondary" onclick="copyToClipboard()">
                                            Copy Link
                                            <i class="fa fa-copy"></i>
                                        </button>
                                    </div>
                                    <script>
                                        function copyToClipboard() {
                                            // Get the value of the hidden input field
                                            const copyText = document.getElementById("linkInput").value;

                                            // Use the Clipboard API to copy the value
                                            navigator.clipboard.writeText(copyText)
                                                .then(() => {
                                                    alert("Link copied to clipboard! \n ");
                                                })
                                                .catch(err => {
                                                    console.error("Failed to copy the link: ", err);
                                                });
                                        }
                                    </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <strong>Commission Summary</strong>
                        </div>
                        <div class="card-body">
                            <dl>
                                <b>Total Commission :</b>
                                $ {{$totalcommission}}
                            </dl>
                            <dl>
                                <b>Total Paid :</b>
                                $ {{number_format($totalpayment,2)}}
                            </dl>
                            <dl>
                                <b>Outstanding Commission :</b>
                                $ @if($dueamount == -0)0 @else{{$dueamount}}@endif
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <strong>Transaction Timeline</strong>
                        </div>

                        <div class="chart">
                            <canvas id="agentBarChart" style="height:350px"></canvas>
                        </div>

                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-group mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0">{{$totaltransaction}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total
                                                Transaction
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
                                        <div class="h4 mb-0">{{$totalsender}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total Senders
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
                                        <div class="h4 mb-0 font-lg">AUD {{$totalsendingamount}} |
                                            NPR {{$totalpaymentamount}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total Amount
                                                Sent
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
                                        @if(Auth::user()->level_id != 7)
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#senderAdded" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Senders Added</a>
                                        </li>
                                        @endif
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
                                                <table id="exampleTransaction"
                                                       class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>

                                                        <th>ID</th>
                                                        <th>Sender Name</th>
                                                        <th>Beneficiary Name</th>
                                                        <th>Sending Amount</th>
                                                        <th>Payment Amount</th>
                                                        <th>Rate</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($agent_transaction as $transaction)
                                                        <tr>

                                                            <td>{{format_id($transaction->id,'T')}}</td>
                                                            <td>{{getSenderName($transaction->sender_id)}}</td>

                                                            <td>{{getBeneficiaryName($transaction->beneficiary_id)}}</td>
                                                            <td>{{$transaction->sending_amount}}</td>
                                                            <td>{{$transaction->payment_amount}}</td>
                                                            <td>{{$transaction->exchange_rate}}</td>
                                                            <td>{{$transaction->name}}</td>
                                                            <td>

                                                                <a href="{{route('transactions.show',$transaction->id)}}"
                                                                   data-toggle="tooltip" data-placement="bottom"
                                                                   title="View"
                                                                   class="btn btn-sm btn-success"><i
                                                                            class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>
                                        @if(Auth::user()->level_id != 7)
                                        <div class="tab-pane" id="senderAdded" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <table id="exampleTransaction"
                                                       class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>

                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Added By</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($senders as $sender)
                                                        <tr>

                                                            <td>{{format_id($sender->sender_id,'S')}}</td>
                                                            <td>{{$sender->full_name}}</td>


                                                            <td>{{$sender->email}}</td>
                                                            <td>{{$sender->number}}</td>
                                                            <td>{{get_user_name($sender->added_by)}}</td>
                                                            <td>{{$sender->status}}</td>
                                                            <td>

                                                                <a href="{{route('sender.show',$sender->sender_id)}}"
                                                                   data-toggle="tooltip" data-placement="bottom"
                                                                   title="View"
                                                                   class="btn btn-sm btn-success"><i
                                                                            class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                            </div>

                                        </div>
                                        @endif
                                        <div class="tab-pane" id="paymentsummary" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <div class="card-body">


                                                    <div class="row">
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card" style="    width: 225px;">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-primary p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-primary mb-0 pt-3">
                                                                        $ {{$totalcommission}}</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs">
                                                                        Total Commission
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/.col-->
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-success p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-info mb-0 pt-3">
                                                                        $ {{number_format($totalpayment,2)}}</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs">
                                                                        Total Paid Amount
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/.col-->
                                                        <div class="col-sm-12 col-lg-4">
                                                            <div class="card">
                                                                <div class="card-body p-0 clearfix">
                                                                    <i class="fa fa-money bg-red p-4 px-5 font-2xl mr-3 float-left"></i>
                                                                    <div class="h6 text-warning mb-0 pt-3">
                                                                        $ @if($dueamount == -0)
                                                                            0 @else{{$dueamount}}@endif</div>
                                                                    <div class="text-muted text-uppercase font-weight-bold font-xs">
                                                                        Due Amount
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
                                                                <th scope="col">Commission Amount</th>
                                                                <th scope="col">Paid Amount</th>
                                                                <th scope="col">Balance</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php $data=array_reverse($payment_summary);
                                                            @endphp
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
                                                            <th>Payment Method</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>

                                                        </thead>
                                                        <tbody>
                                                        @foreach($agent_payments as $payment)
                                                            <tr>

                                                                <td>{{$payment->id}}</td>
                                                                <td>{{$payment->date}}</td>
                                                                <td>{{$payment->amount}}</td>
                                                                <td>{{$payment->method}}</td>
                                                                <td>{{$payment->description}}</td>
                                                                <td>

                                                                    <a href="{{route('agent.payment.edit',$payment->id)}}"
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
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
                    font: function (context) {
                        var w = context.chart.width;
                        return {
                            size: w < 512 ? 12 : 14
                        }
                    },
                    formatter: function (value, context) {
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

        var ctxAgentBarChart = document.getElementById('agentBarChart').getContext('2d');
        ctxAgentBarChart.height = 500;
        var agentBarChart = new Chart(ctxAgentBarChart, {
            type: 'horizontalBar',

            data: {
                labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'oct', 'nov', 'dec'],
                datasets: [{
                    label: 'Sending Amount',
                    backgroundColor: '#ff2da4',
                    borderColor: '#ff2da4',
                    borderWidth: 1,
                    data: []
                },
                    {
                        label: 'Transaction Number',
                        backgroundColor: '#3f53ff',
                        borderColor: '#3f53ff',
                        borderWidth: 1,
                        data: []
                    }],
                options: {
                    maintainAspectRatio: false,
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
                }
            }
        })
    </script>

@endsection
@section('page-script')

    <script>
        fetchAgentBarChartData();

        function fetchAgentBarChartData() {
            url = '/fetchAgentBarChartData';
            @if(isset($agent->agent_id))
                agentId = "{{$agent->agent_id}}";
            $.ajax({
                url: url,
                method: 'GET',
                data: {id: agentId},
                dataType: 'json',
                success: function (data) {
                    var value1 = [];
                    var value2 = [];
                    var keys1 = Object.keys(data.sendingAmountPerMonth);
                    for (var counter = 0; counter < keys1.length; counter++) {
                        value1 [counter] = data.sendingAmountPerMonth[keys1[counter]]
                    }
                    var keys2 = Object.keys(data.transactionCount);
                    for (var counter = 0; counter < keys2.length; counter++) {
                        value2 [counter] = data.transactionCount[keys2[counter]]
                    }
                    agentBarChart.data.labels = keys1;
                    agentBarChart.data.datasets[0].data = value1;
                    agentBarChart.data.datasets[1].data = value2;
                    agentBarChart.update();
                }
            });
            @endif


        }

    </script>
@endsection

