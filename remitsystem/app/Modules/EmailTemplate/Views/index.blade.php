@extends('layouts.main')
@section('title', 'Email Templates')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">Email Templates</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Email Templates
                <div class="pull-right">
                    <a href="{{ route('email-template.create') }}" class="btn btn-primary btn-sm">Add
                        Template</a>
                </div>
            </div>
            <div class="card-body" >
<div style="overflow-x: auto;">
    <table  class="table table-striped table-bordered datatable" id="users">
        <thead>
        <tr>
            <th> Id</th>
            <th>Application Name</th>
            <th>Type</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Action</th>

        </tr>
        </thead>
        <tbody>
        @foreach($templates as $template)
            @php
            @endphp

            <tr>
                <th>{{ $template->id }}</th>
                <td>{{($template->application_id !=0)?getApplicationDetail('id',$template->application_id)->name:'Default'}}</td>
                <td>{{$template->type}}</td>
                <td>{{$template->subject}}</td>
                <td>{!! $template->message !!}</td>
                <td>
                    <a href="{{route('email-template.edit', [$template->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                class="fa fa-edit"></i></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

            </div>
        </div>
    </div>
@endsection





