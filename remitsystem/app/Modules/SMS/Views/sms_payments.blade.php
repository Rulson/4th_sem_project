@extends('layouts.main')
@section('title', 'SMS Payments')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">SMS</li>
    <li  class="breadcrumb-item active">SMS Payments</li>
@stop


@section('content')

    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Payment List

            </div>
            <div class="box-body">
                <table id="users" class="table table-bordered table-striped">
                    <thead>
                    <tr>

                        <th>SMS payment Id</th>
                        <th>Amount</th>
                        <th>Credit Purchase</th>
                        <th>Payment Date</th>
                        <th>Payment Type</th>
                         </tr>
                    </thead>
                    <tbody>
                    @foreach($sms_payment as $sms)
                        <tr>
                            <td>{{ format_id($sms->sms_payment_id, 'P') }}</td>
                            <td>{{$sms->amount}}</td>
                            <td>{{$sms->sms_credit}}</td>
                            <td>{{format_date($sms->created_at)}}</td>
                            <td>{{$sms->payment_type}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#users').DataTable({
                "pageLength": 10,
                'scrollX': true,
                order: [[0, 'desc']],
                dom: 'lBfrtip',
                autoWidth:false,
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    'copy', 'csv', 'excel', 'print'
                ],
            });
        });
    </script>
@stop