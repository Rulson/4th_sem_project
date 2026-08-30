@extends('layouts.main')
@section('title', 'Dashboard')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dashboard</li>
@stop
@section('content')
    <style>
        @media (max-width: 450px) {
            .no-left-padding-on-small{
                padding-left: 5px !important;
            }
            .no-right-padding-on-small{
                padding-right: 5px !important;
            }
        }
    </style>

    <div class="row">

        <div class="col-md-4 ">
            <div class="card text-white bg-light">
                <div class="card-body">
                    <div class="text-center">
                        <p style="font-size: 25px; color: red">1 AUS = NRs {{ $exchangerate }}</p>
                    </div>
                    <div class="text-center">
                        <a href="{{route('transaction.sendmoney')}}" class="btn btn-primary center-block" role="button" style="margin: auto">Send Money</a>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="col-md-3">--}}
{{--            <div class="card text-white bg-light">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="text-center">--}}
{{--                        <p style="font-size: 25px; color: green"><i class="fa fa-trophy" style="color: gold;"></i> {{$referral_points}}</p>--}}
{{--                    </div>--}}
{{--                    <div class="text-center">--}}
{{--                        <p style="font-size: 18px; color: green">Referral Points</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-md-3 col-6 no-right-padding-on-small">
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

        <div class="col-md-3 col-6 no-left-padding-on-small">
            <a href="{{route('transactions.orders')}}" style="text-decoration : none">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <button type="button" class="btn btn-transparent p-0 float-right">
                            <i class="fa fa-dollar fa-stack-2x" style="margin-left: 64px;" ></i>
                        </button>
                        <p style="font-size: 20px">Transaction</p>
                    </div>
                </div>
            </a>
        </div>
        <!--/.col-->
        <div class="col-md-3 col-6 no-right-padding-on-small">
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

        <div class="col-md-3 col-6 no-left-padding-on-small">
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
                        <h4>@if(isset($total_month_au)){{'$ '.number_format($total_month_au,2)}}@endif</h4>
                    </div>
                    <div class="pull-right">
                        <h4>@if(isset($total_month_np)){{'NRs '.number_format($total_month_np,2)}}@endif</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <p style="font-size:20px">All Time</p>
                    <div class="pull-left">
                        <h4>@if(isset($total_amount_au)){{'$ '.number_format($total_amount_au,2)}}@endif</h4>
                    </div>
                    <div class="pull-right">
                        <h4>@if(isset($total_amount_np)){{'NRs '.number_format($total_amount_np,2)}}@endif</h4>
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @include('User::partials.bankDetailModal')
            </div>
        </div>
    </div>

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
            "type":"line",
            "data":{
                "datasets":[{
                    "label":"Exchange Rate",
                    "data":[0,0,0,0,0,0,0,0],
                    backgroundColor: '#ffd7ee',
                    borderColor: '#ff7f9b',
                    "fill":true,
                    // "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1,
                }]},
            "options":{

                scaleShowLabels : false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100,
                        }
                    }]
                }
            }});
    </script>



    <!-- /.content -->
@stop
@section('page-script')
    @include('layouts.common')

@stop
