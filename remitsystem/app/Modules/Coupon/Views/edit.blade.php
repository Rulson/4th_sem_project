@extends('layouts.main')
@section('title', 'Edit Coupon')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('coupons.index')}}" title="All Senders">Coupon</a></li>
    <li class="breadcrumb-item active">Update</li>

@stop
@section('content')

            @include('flash::message')

            {!!Form::model($coupon, array('route' => array('coupon.update', $coupon->id), 'class' => '', 'method' => 'post'))!!}
            <div class="animated fadeIn">
                @include('Coupon::form')

            </div>
            <!--<input type="submit" class="btn btn-primary pull-right" value="Update" />-->
            <button type="submit" class="btn btn-primary" value="Update" >Update</button>
                        {!!Form::close()!!}

@stop
