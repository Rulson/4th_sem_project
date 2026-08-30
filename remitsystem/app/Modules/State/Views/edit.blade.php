@extends('layouts.main')
@section('title', 'Edit States-District')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('states.index')}}" title="All Senders">State/District</a></li>
    <li class="breadcrumb-item active">Update</li>

@stop
@section('content')

            @include('flash::message')

            {!!Form::model($state, array('route' => array('state.update', $state->id), 'class' => '', 'method' => 'post'))!!}
            <div class="animated fadeIn">
                @include('State::form')

            </div>
            <!--<input type="submit" class="btn btn-primary pull-right" value="Update" />-->
            <button type="submit" class="btn btn-primary" value="Update" >Update</button>
                        {!!Form::close()!!}

@stop
