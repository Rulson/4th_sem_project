@extends('layouts.main')
@section('title', 'Add States-District')
@section('heading', 'Add States-District')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('states.index')}}" title="All Senders">State-District</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop
@section('content')
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">State/District</h3>
            </div>
            @include('flash::message')
            {!!Form::open(array('route' => ['state.store']))!!}
            <div class="box-body">
                @include('State::form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" value="Save">Save</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@stop
