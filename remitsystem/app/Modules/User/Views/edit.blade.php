@extends('layouts.main')
@section('title', 'Edit User')
@section('breadcrumb')
    @parent
    @if(Auth::user()->level_id ==1 || Auth::user()->level_id == 2)

        <li class="breadcrumb-item"><a href="{{url('users')}}" title="All Users">Users</a></li>
        <li class="breadcrumb-item active">Update</li>

    @else
        <li class="breadcrumb-item">Users</li>

    @endif

@stop
@section('content')
            @include('flash::message')
            {!!Form::model($user, array('route' => array('user.update', $user->user_id), 'class' => '','enctype'=>'multipart/form-data', 'method' => 'post'))!!}
            <div class="animated fadeIn">

            @include('User::form')

                <input type="submit" class="btn btn-primary pull-right" value="Save" />
            </div>
            {!!Form::close()!!}

@stop
