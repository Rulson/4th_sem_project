@extends('layouts.main')
@section('title', 'Email Logs')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Notifications</li>
    <li  class="breadcrumb-item active">Notification Logs</li>
@stop


@section('content')

    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Notifications List
            </div>
            <div class="card-body">
                <table id="users" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Application</th>
                        <th>Url</th>
                        <th>Send By</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($notifications as $notification)
                            <tr>
                              <td>{{$notification->id}}</td>
                              <td>{{$notification->title}}</td>
                              <td>{!! $notification->notification_message !!}</td>
                              <td>{!! $notification->application_name !!}</td>
                              <td>{!! $notification->url !!}</td>
                              <td>{{getUserNameById($notification->user_id)}}</td>
                              <td>{{standard_date($notification->created_at)}}</td>
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
