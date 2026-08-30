@extends('layouts.main')
@section('title', 'Edit Beneficiary')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('beneficiaries.index')}}" title="All Senders">Beneficiaries</a></li>
    <li class="breadcrumb-item active">Update</li>

@stop
@section('content')

            @include('flash::message')

            {!!Form::model($beneficiary, array('route' => array('beneficiary.update', $beneficiary->beneficiary_id), 'class' => '', 'method' => 'post'))!!}
            <div class="animated fadeIn">
                @include('Beneficiary::Client/form')

            </div>
            <!--<input type="submit" class="btn btn-primary pull-right" value="Update" />-->
            <button type="submit" class="btn btn-primary pull-right" value="Update" >Update</button>
                        {!!Form::close()!!}

@stop
