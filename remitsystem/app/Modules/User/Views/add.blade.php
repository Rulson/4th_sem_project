@extends('layouts.main')
@section('title', 'Add User')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{url('users')}}" title="All Users">Users</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop
@section('content')
    @include('flash::message')
    {!!Form::open(array('route' => ['user.store']))!!}
    <div class="animated fadeIn">
        @include('User::form')
        <input type="submit"  class="btn btn-primary" value="Save" />
    </div>
    {!!Form::close()!!}
@stop

