@extends('layouts.main')
@section('title', 'Add Coupon')
@section('heading', 'Add Coupon')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('states.index')}}" title="All Senders">Coupon</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop
@section('content')
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Coupon</h3>
            </div>
            @include('flash::message')
            {!!Form::open(array('route' => ['coupon.store']))!!}
            <div class="box-body">
                @include('Coupon::form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" value="Save">Save</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@stop
