@extends('layouts.main')
@section('title', 'SMS Sent')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">SMS</li>
    <li  class="breadcrumb-item active">SMS Logs</li>
@stop


@section('content')

    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                SMS List

            </div>
            <div class="card-body">
                <table id="users" class="table table-bordered table-striped">
                    <thead>
                    <tr>

                        <th>Id</th>
                        <th>Transaction Id</th>
                        <th>Sender</th>
                        <th>Message</th>
                        <th>Receiver</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sms as $sms)
                        <tr>
                            <td>{{ format_id($sms->id, 'S') }}</td>
                            <td>{{$sms->transaction_id}}</td>
                            <td>{{get_user_name($sms->send_from)}}</td>
                            <td>{{$sms->message}}</td>
                            <td>{{getPersonName($sms->receiver_id)}}</td>
                            <td>{{format_date($sms->created_at)}}</td>
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
                "pageLength": 50,
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