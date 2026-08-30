@extends('layouts.main')
@section('title', 'All Senders')
@section('heading', 'All Senders')
@section('breadcrumb')
    @parent
    <li class="active">All Senders</li>
@stop

@section('content')
<style>
    .padding-10{
        padding-left: 5px !important;
        padding-right: 5px !important;

    }
</style>
    <section class="content">
        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Agent Senders</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="count-box">
                            <div class="inner">
                                <h3><a href="#">{{$agent_senders_total}}</a></h3>

                                <p>Total Agent Senders</p>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix" style="padding:5px !important;">
                        <div class="col-md-4 padding-10">
                        <div>
                            <div class="small-box bg-aqua" >
                                <div class="inner">
                                    <h4>{{$agent_senders_today}}</h4>
                                    <h6>Today</h6>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 padding-10">
                        <div>
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h4>{{$agent_senders_this_week}}</h4>

                                    <h6>This week</h6>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 padding-10">
                        <div >
                            <div class="small-box bg-yellow" >
                                <div class="inner">
                                    <h4>{{$agent_senders_this_month}}</h4>

                                    <h6>This Month</h6>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                </div><!-- /.box -->
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Admin Senders</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="count-box">
                            <div class="inner">
                                <h3><a href="#">{{$admin_senders_total}}</a></h3>

                                <p>Total Admin Senders</p>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix" style="padding:5px !important;">
                        <div class="col-md-4 padding-10">
                            <div>
                                <div class="small-box bg-aqua" >
                                    <div class="inner">
                                        <h4>{{$admin_senders_today}}</h4>
                                        <h6>Today</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 padding-10">
                            <div>
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h4>{{$admin_senders_this_week}}</h4>

                                        <h6>This week</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 padding-10">
                            <div >
                                <div class="small-box bg-yellow" >
                                    <div class="inner">
                                        <h4>{{$admin_senders_this_month}}</h4>

                                        <h6>This Month</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- /.box -->
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Client Senders</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="count-box">
                            <div class="inner">
                                <h3><a href="#">{{$client_senders_total}}</a></h3>

                                <p>Total Client Senders</p>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix" style="padding:5px !important;">
                        <div class="col-md-4 padding-10">
                            <div>
                                <div class="small-box bg-aqua" >
                                    <div class="inner">
                                        <h4>{{$client_senders_today}}</h4>
                                        <h6>Today</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 padding-10">
                            <div>
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h4>{{$client_senders_this_week}}</h4>

                                        <h6>This week</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 padding-10">
                            <div >
                                <div class="small-box bg-yellow" >
                                    <div class="inner">
                                        <h4>{{$client_senders_this_month}}</h4>

                                        <h6>This Month</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- /.box -->
            </div>
        </div>
        @endif
        @include('flash::message')

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Sender List</h3>

                <a href="{{ route('sender.create') }}" class="btn btn-primary btn-flat pull-right">Add
                    New Sender</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="senders" class="table table-bordered table-striped">
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
    </section>
@endsection

@section('page-script')
    <script type="text/javascript">
             $(document).ready(function () {

            dtable = $('#senders').dataTable({
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
