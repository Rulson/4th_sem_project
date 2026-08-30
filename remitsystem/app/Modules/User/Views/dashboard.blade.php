@extends('layouts.main')
@section('title', 'Dashboard')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dashboard</li>
@stop
{{--copy link css--}}

@section('content')
    @if(Auth::user()->level_id != 5)
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="card text-white bg-info">
                    <div class="card-body">

                        <button type="button" class="btn btn-transparent p-0 pull-right">
                            <i class="fa fa-shopping-cart"></i>
                        </button>

                        <h4 class="mb-0">@if(isset($all_transactions_count))
                                {{$all_transactions_count}}
                            @endif</h4>
                        <p>Total Orders</p>
                    </div>
                </div>
            </div>
            <!--/.col-->
            @if(in_array(Auth::user()->level_id, [1,2,3,6]))
                <div class="col-sm-6 col-lg-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <button type="button" class="btn btn-transparent p-0 float-right">
                                <i class="icon-user-follow"></i>
                            </button>
                            <h4 class="mb-0">@if(isset($total_senders))
                                    {{$total_senders}}
                                @endif</h4>
                            <p>Total Senders</p>
                        </div>
                    </div>
                </div>
            @endif
            <!--/.col-->

            <div class="col-sm-6 col-lg-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <button type="button" class="btn btn-transparent p-0 float-right">
                            <i class="fa fa-users"></i>
                        </button>
                        <h4 class="mb-0">@if(isset($total_beneficiaries))
                                {{$total_beneficiaries}}
                            @endif</h4>
                        <p>Total Beneficiaries</p>
                    </div>
                </div>
            </div>
            <!--/.col-->


            <!--/.col-->
        </div>
        @if(Auth::user()->level_id == 3)
            <div class="row">
                <div class="col">
                    <div class="pb-3">
                        <span style="margin-right: 10px">My Shareable Link:</span> <input type="text" style="display: none" readonly
                                                  value="{{_generateAgentShareableLink(getAgentId())}}" id="linkInput">
                        <button type="button" class="btn btn-secondary" onclick="copyToClipboard()">
                            Copy Link
                            <i class="fa fa-copy"></i>
                        </button>
                        <a type="button" href="{{$agentQRCode}}" class="btn btn-secondary" target="_blank" style="margin-left: 10px;">Open QR</a>
                        <a type="button" download="agent_qr.png" id="downloadLink" class="btn btn-secondary"  style="margin-left: 10px;">Download QR</a>
                        <script defer>
                            async function downloadQR(){
                                const downloadLink = document.getElementById('downloadLink');
                                const apiUrl = "{!!$agentQRCode!!}";
                                const response = await fetch(apiUrl);
                                const blob = await response.blob();
                                downloadLink.href = URL.createObjectURL(blob);
                            }
                            downloadQR();
                        </script>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Latest Orders
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Sender</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($orders))
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->transaction_id}}</td>
                                        <td>{{$order->first_name}} {{$order->last_name}}</td>
                                        <td>
                                            @if($order->status == 1)
                                                <span class="badge badge-blue">Transferred</span>
                                            @endif
                                            @if($order->status == 2)
                                                <span class="badge process_in_nepal">Process In Nepal</span>
                                            @endif
                                            @if($order->status == 3)
                                                <span class="badge badge-yellow">Process To Nepal</span>
                                            @endif
                                            @if($order->status == 4)
                                                <span class="badge receipt_recieved">Receipt Received</span>
                                            @endif
                                            @if($order->status == 5)
                                                <span class="badge ready_to_transfer">Ready To Transfer</span>
                                            @endif
                                            @if($order->status == 6)
                                                <span class="badge badge-secondary">Ready To Collect</span>
                                            @endif
                                            @if($order->status == 7)
                                                <span class="badge badge-secondary">Processing</span>
                                            @endif
                                            @if($order->status == 8)
                                                <span class="badge badge-secondary">Pending Delivery</span>
                                            @endif
                                            @if($order->status == 9)
                                                <span class="badge badge-secondary">Pending</span>
                                            @endif
                                            @if($order->status == 10)
                                                <span class="badge paid">Paid</span>
                                            @endif
                                            @if($order->status == 11)
                                                <span class="badge ime_ready">IME Ready</span>
                                            @endif
                                            @if($order->status == 12)
                                                <span class="badge cancelled">Cancelled</span>
                                            @endif
                                            @if($order->status == 13)
                                                <span class="badge bank_ready">Bank Ready</span>
                                            @endif
                                            @if($order->status == 14)
                                                <span class="badge awaiting_payment">Awaiting Payment</span>
                                            @endif
                                            @if($order->status == 15)
                                                <span class="badge awaiting_payment">Hold</span>
                                            @endif
                                            @if($order->status == 16)
                                                <span class="badge awaiting_payment">Pending Amount</span>
                                            @endif
                                            @if($order->status == 17)
                                                <span class="badge awaiting_payment">Pending Processing</span>
                                            @endif
                                            @if($order->status == 18)
                                                <span class="badge awaiting_payment">Review Payment</span>
                                            @endif
                                            @if($order->status == 19)
                                                <span class="badge badge-secondary">Excess Payment</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="">
                            <a class="btn btn-info btn-sm" href="{{route('transactions.orders')}}">View All Orders</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Orders
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>No of order</th>
                                <th>AUD</th>
                                <th>NPR</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($txn_count as $key=>$value)
                                <tr>
                                    <td><a href="{{url('/orders')}}?status={{$key}}">{{getStatusName($key)}}</a></td>
                                    <td>{{$value['count']}}</td>
                                    <td>{{$value['sending_amount']}}</td>
                                    <td>{{$value['payment_amount']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @if(Auth::user()->level_id == 5)
                <div class="row">
                    <div class="col-md-4 offset-md-4">
                        <div class="card text-white bg-light">
                            <div class="card-body">
                                <div class="text-center">
                                    <p style="font-size: 25px; color: red">1 AUS = NRs {{ $exchangerate }}</p>
                                </div>
                                <div class="text-center">
                                    <a href="{{route('transaction.sendmoney')}}" class="btn btn-primary center-block"
                                       role="button" style="margin: auto">Send Money</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-6">
                        <a href="" style="text-decoration : none" data-toggle="modal" data-target="#exampleModal">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <button type="button" class="btn btn-transparent p-0 pull-right">
                                        <i class="fa fa-bank fa-stack-2x" style="margin-left: 64px;"></i>
                                    </button>
                                    <p style="font-size: 20px">Our Bank</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--/.col-->
                    <!--/.col-->

                    <div class="col-md-3 col-6">
                        <a href="{{route('transactions.orders')}}" style="text-decoration : none">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <button type="button" class="btn btn-transparent p-0 float-right">
                                        <i class="fa fa-dollar fa-stack-2x" style="margin-left: 64px;"></i>
                                    </button>
                                    <p style="font-size: 20px">Transaction</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--/.col-->
                    <div class="col-md-3 col-6">
                        <a href="{{route('beneficiaries.index')}}" style="text-decoration : none">
                            <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <button type="button" class="btn btn-transparent p-0 float-right">
                                        <i class="fa fa-users fa-stack-2x" style="margin-left: 64px;"></i>
                                    </button>
                                    <p style="font-size: 20px;">Receivers</p>
                                </div>

                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 col-6">
                        <a href="{{route('transaction.sendmoney')}}" style="text-decoration : none">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <button type="button" class="btn btn-transparent p-0 float-right">
                                        <i class="fa fa-paper-plane fa-stack-2x" style="margin-left: 64px;"></i>
                                    </button>
                                    <p style="font-size: 20px;">Send Money</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <p style="font-size:20px">This Month</p>
                                <div class="pull-left">
                                    <h4>@if(isset($total_month_au))
                                            {{'$ '.number_format($total_month_au,2)}}
                                        @endif</h4>
                                </div>
                                <div class="pull-right">
                                    <h4>@if(isset($total_month_np))
                                            {{'NRs '.number_format($total_month_np,2)}}
                                        @endif</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card text-white bg-secondary">
                            <div class="card-body">
                                <p style="font-size:20px">All Time</p>
                                <div class="pull-left">
                                    <h4>@if(isset($total_amount_au))
                                            {{'$ '.number_format($total_amount_au,2)}}
                                        @endif</h4>
                                </div>
                                <div class="pull-right">
                                    <h4>@if(isset($total_amount_np))
                                            {{'NRs '.number_format($total_amount_np,2)}}
                                        @endif</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--/.col-->
                </div>
                <br><br>
                <div class="col-md-12">
                    <canvas id="exchangerate" width="400" height="150"></canvas>
                </div>
            @endif
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @include('User::partials.bankDetailModal')
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0/dist/chartjs-plugin-datalabels.min.js"></script>
        <script>
            function addData(chart, label, data) {
                chart.data.labels = label;
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
                    datalabels: false
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


            var ctxtransaction = document.getElementById('exchangerate').getContext('2d');
            var exchange = new Chart(ctxtransaction, {
                "type": "line",
                "data": {
                    "datasets": [{
                        "label": "Exchange Rate",
                        "data": [0, 0, 0, 0, 0, 0, 0, 0],
                        backgroundColor: '#ffd7ee',
                        borderColor: '#ff7f9b',
                        "fill": true,
                        // "borderColor":"rgb(75, 192, 192)",
                        "lineTension": 0.1,
                    }]
                },
                "options": {

                    scaleShowLabels: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 100,
                            }
                        }]
                    }
                }
            });
        </script>


        {{--    copy link js--}}
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
        <!-- /.content -->
        @stop
        @section('page-script')
            @include('layouts.common')

        @stop
