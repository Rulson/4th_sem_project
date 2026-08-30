@extends('layouts.main')
@section('title', 'Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active"><a href="{{route('applications.index')}}" title="All Distributors">Applications</a></li>
    <li class="breadcrumb-item active">{{$data->name}}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="sendmoney-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Application Details</strong>
                            <div class="pull-right">
                                @if(Auth::user()->level_id != 6)
                                <a href="{{route('application.create')}}"
                                   class="btn btn-success  btn-sm pull-right"><i class="fa fa-plus">
                                        Add Application</i></a>
                                    @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email Sender</th>
                                    <td>{{ $data->email }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Email</th>
                                    <td>{{ $data->contact_email }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $data->state }} {{ $data->street }} {{ $data->postcode }} {{get_country($data->country_id)}}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ $data->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th>Agent</th>
                                    <td>{{getAgentNameByUserId($data->agent_id)}}</td>
                                </tr>
                                <tr>
                                    <th>Package Name</th>
                                    <td>{{$data->package_name}}</td>
                                </tr>
                                <tr>
                                    <th>Domain URL</th>
                                    <td>{{$data->domain_url}}</td>
                                </tr>
                                <tr>
                                    <th>Firebase Key</th>
                                    <td>{{$data->firebase_key}}</td>
                                </tr>
                                <tr>
                                    <th>Play Store URL</th>
                                    <td>{{$data->playstore_url}}</td>
                                </tr>
                                <tr>
                                    <th>App Store URL</th>
                                    <td>{{$data->appstore_url}}</td>
                                </tr>
                                <tr>
                                    <th>Alert</th>
                                    <td>{{$data->alert}}</td>
                                </tr>
                                <tr>
                                    <th>Alert Link</th>
                                    <td>{{$data->alert_link}}</td>
                                </tr>
                                <tr>
                                    <th>Publish</th>
                                    <td>{{ ($data->published == 1)? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <th>Logo</th>
                                    <td><img src="{{ url('/application/'.$data->logo) }}" height="50px" width="50px"></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{!! $data->description  !!}</td>
                                </tr>
                                <tr>
                                    <th><a href="{{route('application.delete', [$data->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure ,you want to delete this application?')"><i
                                                class="fa fa-trash"></i> Delete</a></th>
                                    <td><a href="{{route('application.edit', [$data->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                                class="fa fa-edit"></i> Edit</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .due-amount .card-body {
            padding: 10px;
        }
    </style>
    <style>
        .list-group-item {
            cursor: pointer;
        }

        .tt-menu {
            width: 100% !important;
        }

        .typeahead {
            positin: relative;
        }

        .Typeahead-spinner {
            position: absolute;
            right: 93px;
            top: 23px;
            display: none;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

@endsection


