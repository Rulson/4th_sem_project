@extends('layouts.main')
@section('title', 'Rates')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Rates</li>
    <li class="breadcrumb-item active">All</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                All Rates List
                @if(in_array(Auth::user()->level_id,[1,8]))
                <div class="pull-right">
                    <a href="{{route('add.rate')}}" class="btn btn-primary btn-sm">Add New Rate</a>
                </div>
                    @endif
            </div>

            <div class="card-body">
                <table class="table table-responsive-sm table-bordered table-striped table-sm">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer Rate</th>
                      @if(in_array(Auth::user()->level_id,[1,8]))
                            <th>Cost Rate</th>
                      @endif
                        <th>Agent Rate</th>
                        <th>Threshold Amount</th>
                      @if(in_array(Auth::user()->level_id,[1,8]))
                            <th>Action</th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rates as $data)
                        <tr>
                            <td>{{standard_date($data->created_at)}}</td>
                            <td>{{$data->exchange_rate}}</td>
                            @if(in_array(Auth::user()->level_id,[1,8]))
                                <td>{{$data->cost_rate}} </td>
                            @endif
                            <td>{{$data->agent_rate}}</td>
                            <td>{{$data->threshold_amount}}</td>
                            @if(in_array(Auth::user()->level_id,[1,8]))
                                <td> <a href="{{route('edit.rate',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                    class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                            </td>
                                @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <ul class="pagination">
                    {{$rates->links()}}

                </ul>
            </div>

        </div>
    </div>


@endsection


