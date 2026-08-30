@extends('layouts.main')
@section('title', 'Add Beneficiary')
@section('heading', 'Add Beneficiary')
@section('breadcrumb')
    @parent
    <li><a href="{{route('beneficiaries.index')}}" title="All Senders"><i class="fa fa-senders"></i> Beneficiaries</a></li>
    <li>Add</li>
@stop
@section('content')
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Beneficiaries Details</h3>
            </div>
            @include('flash::message')
            {!!Form::open(array('route' => ['beneficiary.store']))!!}
            <div class="box-body">
                @include('Beneficiary::form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right" value="Save">Save</button>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@stop
