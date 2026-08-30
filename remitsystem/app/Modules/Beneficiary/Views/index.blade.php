@extends('layouts.main')
@section('title', 'All Beneficiaries')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Beneficiaries</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Beneficiary  List
            </div>
            <div class="card-body">
                <table id="beneficiaries" class="table table-striped table-bordered datatable">
                    <thead>
                    <tr>
                        <th>Beneficiary Id</th>
                        <th>Date Joined</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Sender Name</th>
                        <th>Added By</th>
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

             $('#beneficiaries').dataTable({
                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                 "autoWidth" : false,
                "ajax": "/beneficiaries/getBeneficiariesData",
                "columns": [
                    {data: 'beneficiary_id', name: 'beneficiary_id'},
                    {data: 'datejoined', name: 'datejoined', searchable: false},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'number', name: 'number'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']],
                 "sScrollX": '100%'
            })
        });
    </script>
@stop
