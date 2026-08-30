@extends('layouts.main')
@section('title', 'All Referrals')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Referrals</li>
@stop
@section('content')
    <div class="animated fadeIn">

        <div class="row">
            <div class="col-sm-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-4">
                            <i class="fa fa-money custom"></i>
                        </div>
                        <div class="text-value-lg">{{$referral_counts->no_of_approved}} / {{$referral_counts->no_of_referrals}}</div><small class="text-muted text-uppercase font-weight-bold">No of Approved / No of Referrals</small>
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="col-sm-4 col-md-4">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="h1 text-muted text-right mb-4">--}}
{{--                            <i class="fa fa-money custom"></i>--}}
{{--                        </div>--}}
{{--                        <div class="text-value-lg">{{$referral_points->total_used}} / {{$referral_points->total_claimed}}</div><small class="text-muted text-uppercase font-weight-bold">--}}
{{--                            Total Points Used / Total Points Claimed </small>--}}
{{--                        <div class="progress progress-xs mt-3 mb-0">--}}
{{--                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="col-sm-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-4">
                            <i class="fa fa-money custom"></i>
                        </div>
                        <div class="text-value-lg">
                            @if(in_array(Auth::user()->level_id, [1,2]))
                            {{$referral_counts->no_of_referral_users}}
                                @endif
                            @if(Auth::user()->level_id == 5)
                                {{$referral_counts->no_of_clients_referred}}
                                @endif
                        </div><small class="text-muted text-uppercase font-weight-bold">
                            @if(in_array(Auth::user()->level_id, [1,2]))
                                No of Referring Users
                            @endif
                                @if(Auth::user()->level_id == 5)
                                    No of clients Referred
                                @endif
                        </small>
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Referrals List
                @if(isset($my_referral))
                  @if(Auth::user()->level_id == 5)  <span class="pull-right"> My Referral Code : <strong>{{$my_referral}} </strong> </span> @endif
                    <span class="pull-right"> <strong> 1 </strong> Approved Referral = <strong>1</strong> Free Service Charge &nbsp;</span>
                @endif
            </div>
            <div class="card-body">

                <table id="referrals" class="table table-striped table-bordered datatable">
                    <thead>
                    <tr>
                        <th>Id</th>
                        @if(in_array(Auth::user()->level_id,[1,2]))
                        <th>Referrer</th>
                        @endif
                        <th>Referee</th>
{{--                        <th>Points</th>--}}
                        <th>Status</th>
                        <th>Created At</th>

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
            $('#referrals').dataTable({
                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/referrals/list",
                "columns": [
                    {data: 'id', name: 'id'},
                    @if(in_array(Auth::user()->level_id,[1,2]))
                        {data: 'referrer', name: 'referrer'},
                    @endif
                    {data: 'client_name', name: 'client_name'},
                    // {data: 'discount_percent', name: 'discount_percent'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},

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
