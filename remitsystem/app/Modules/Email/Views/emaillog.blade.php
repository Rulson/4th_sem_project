@extends('layouts.main')
@section('title', 'Email Logs')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Email System</li>
    <li  class="breadcrumb-item active">Email Logs</li>
@stop


@section('content')

    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Emails List

            </div>
            <div class="card-body">
                <table id="users" class="table table-bordered table-striped">
                    <thead>
                    <tr>

                        <th>Id</th>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Receiver</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                  @foreach($email as $e)
                        <tr>
                          <td>{{$e->id}}</td>
                          <td>{{$e->from}}</td>
                          <td>{{$e->subject}}</td>
                          <td>{!! $e->email_message !!}</td>
                          <td>{{$e->receiver}}</td>
                          <td>{{standard_date($e->created_at)}}</td>
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