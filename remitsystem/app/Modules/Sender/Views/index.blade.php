@extends('layouts.main')
@section('title', 'All Senders')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Senders</li>
@stop
@section('content')
    <div class="animated fadeIn">
      <div class="card">
          <div class="card-header">
              Senders List
              @if(Auth::user()->level_id != 6)
              <div class="pull-right">
                  <a href="{{ route('sender.create') }}" class="btn btn-primary btn-sm">Add
                      New Sender</a>
              </div>
              @endif
          </div>
          <div class="card-body">
              <table id="senders" class="table table-striped table-bordered datatable">
                  <thead>
                  <tr>
                      <th>Sender Id</th>
                      <th>Date Joined</th>
                      <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                      <th>Added By</th>
                       <th>Status</th>
                        <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>

                  </tbody>
              </table>
         </div>
      </div>
 </div>
@endsection

@section('page-script')
    <script type="text/javascript">
             $(document).ready(function () {
                 $('#senders').dataTable({
                stateSave: true,
                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/senders/getSendersData",
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'number', name: 'number'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']],
               'scrollX': true,
            })
        });
    </script>
@stop
