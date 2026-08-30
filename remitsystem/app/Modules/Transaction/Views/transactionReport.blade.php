@extends('layouts.main')
@section('title', 'Transactions Report')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">Transaction Report</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">

            <div class="card-body">
                <form action="{{route('transactions.report.result')}}" method="post" id="search-form">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Filtered Options <input type="submit" class="btn btn-primary pull-right" value="Search">
                            </h3>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>From:</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                    <input type="text" class="form-control pull-right" name="from" id="from" value="@if(isset($search_attributes['from'])){{$search_attributes['from']}} @endif" autocomplete="off">

                                </div>
                            </fieldset>
                      </div>
                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>To:</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                    <input type="text" class="form-control pull-right" name="to" id="to" value="@if(isset($search_attributes['to'])){{$search_attributes['to']}} @endif" autocomplete="off">

                                </div>
                            </fieldset>

                        </div>

                    </div>
                </form>
            </div>
                <div class="card">
                    <div class="card-header">
                        Transaction Report
                    </div>
                    <div class="card-body">

                        <table id="exampleTransactionReport" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Transactions</th>
                                <th>Total Sending Amount</th>
                                <th>Total Payment Amount</th>
                                <th>Total Service Fee</th>
                                <th>Average Rate</th>
                            </tr>
                            </thead>
                            @php $reverse_date = array_reverse($dates_this_week); @endphp
                            <tbody>

                            @foreach($reverse_date as $key=>$value)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$value['transaction_count']}}</td>
                                    <td>AUD {{$value['sending_amount']}}</td>
                                    <td>NPR {{$value['payment_amount']}}</td>
                                    <td>AUD {{$value['service_fee']}}</td>
                                    <td>{{number_format($value['average_rate'],2)}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>


                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>

        $(document).ready(function () {

            dtable = $('#exampleTransactionReport').dataTable({
                "processing": true,
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 10,
                'scrollX': true,
                'autoWidth':false,
                'ordering': false,
            })
        });
    </script>
   @endsection







