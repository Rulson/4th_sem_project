@extends('layouts.main')
@section('title', 'All States-District')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All States/Districts</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                State/District List
                <div class="pull-right">
                    <a href="{{ route('state.create') }}" class="btn btn-primary btn-sm">Add
                        New State</a>
                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped table-bordered datatable table-sm" id="users">
                    <thead>
                    <tr>
                        <th> Id</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Postcode</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($states as $state)

                        <tr>
                            <th>{{ $state->id }}</th>
                            <td>{{$state->name}}</td>
                            <td>{{$state->type}}</td>
                            <td>{{$state->postcode}}</td>
                            <td>
                                <a href="{{route('state.edit', [$state->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





