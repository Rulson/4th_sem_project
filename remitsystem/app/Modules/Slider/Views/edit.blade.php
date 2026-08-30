@extends('layouts.main')
@section('title', 'Edit Slider')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('sliders.index')}}" title="All Senders">Slider</a></li>
    <li class="breadcrumb-item active">Update</li>

@stop
@section('content')

            @include('flash::message')

            {!!Form::model($slider, array('route' => array('slider.update', $slider->id), 'class' => '', 'method' => 'post','files'=>true))!!}
            <div class="animated fadeIn">
                @include('Slider::form')

            </div>
            <!--<input type="submit" class="btn btn-primary pull-right" value="Update" />-->
            <button type="submit" class="btn btn-primary pull-right" value="Update" >Update</button>
                        {!!Form::close()!!}

@stop
