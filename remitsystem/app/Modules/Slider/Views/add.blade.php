@extends('layouts.main')
@section('title', 'Add Slider')
@section('heading', 'Add Slider')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('sliders.index')}}" title="All Senders">Slider</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop
@section('content')
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Slider</h3>
            </div>
            @include('flash::message')
            {!!Form::open(array('route' => ['slider.store'],'files'=>true))!!}
            <div class="box-body">
                @include('Slider::form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" value="Save">Save</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@stop
