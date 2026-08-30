@php use App\Modules\Agent\Models\Agent; @endphp
@extends('layouts.main')
@section('title', 'All Agents')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Manage Agents</li>
    <li  class="breadcrumb-item active">All Agents</li>
@stop
@section('content')
@include('flash::message')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Agent List
                @if(!in_array(Auth::user()->level_id, [2,6,7]))
                <div class="pull-right">
                    <a href="{{ route('agent.create') }}" class="btn btn-primary btn-sm">Add New Agent</a>
                </div>
                    @endif
            </div>
            <div class="card-body">
                <table  id="agents" class="table table-striped table-bordered datatable">
                    <thead>
                    <tr>

                        <th>Customer Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Service Charge</th>
                        <th>Outstanding Commission (AUD) </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $data)
                        <tr>
                            <th>{{format_id($data->id,"A")}}</th>
                            <td><a href="{{route('agent.show',$data->id)}}">{{$data->name}}</a></td>
                            <td>{{$data->email}}</td>
                            <td>{{$data->number}}</td>
                            <td>{{$data->agent_service_charge}}</td>
                            <td>{{getAgentCommission($data->id)-getAgentPayment($data->id)}}</td>
                            <td><a href="{{route('agent.show',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="View"
                                   class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                               @php
                                       $user = App\Modules\Agent\Models\Agent::where('id',$data->id)->first();
                               @endphp
                                @if(!in_array(Auth::user()->level_id, [7,8]) && isset($user->user_id) == false)
                                    <a href="{{route('agent.createuser',$data->id)}}" onclick="return confirm('Are you sure? You want to add him as a user')" data-toggle="tooltip" data-placement="bottom" title="Create User" class="btn btn-sm btn-warning"><i
                                                class="fa fa-user"></i></a>
                                @endif
                                @if(!in_array(Auth::user()->level_id, [2,6,7,8]))
                                    <a href="{{route('agent.edit',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="Edit"
                                   class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                                @endif
                                @if(Auth::user()->level_id == 1)

                                    @php
                                    $unrestrictedAgents = Agent::where('is_restricted', 0)->pluck('id')->toArray();
                                    if(in_array($data->id, $unrestrictedAgents)){
                                        $restrict_text = 'restrict transaction';
                                        $restrict_btn_style = 'btn-warning';
                                    }else{
                                        $restrict_text = 'unrestrict transaction';
                                        $restrict_btn_style = 'btn-success';
                                    }
                                    @endphp

{{--                                    $restrict_btn = '<a href="' . route('senders.toggle-restriction', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="' . ucfirst($restrict_text). '" class="btn btn-sm ' . $restrict_btn_style .'  " onclick="return confirm(\'Are you sure you want to ' . $restrict_text. ' for this sender?\')" ><i--}}
{{--                                            class="fa fa-exclamation-triangle"></i></a>';--}}
                                    <a href="{{route('agents.toggle-restriction',$data->id)}}" data-toggle="tooltip" onclick="return confirm('Are you sure you want to {{$restrict_text}} for this agent?')"  data-placement="bottom" title="{{ucfirst($restrict_text)}}"
                                       class="btn btn-sm {{$restrict_btn_style}}"><i
                                            class="fa fa-exclamation-triangle"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection



