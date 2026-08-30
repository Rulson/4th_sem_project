@extends('layouts.main')
@section('title', 'Edit Bank')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('banks.index')}}" title="All Senders">Banks</a></li>
    <li class="breadcrumb-item active">Edit</li>

@stop
@section('content')

    <div class="animated fadeIn">

        <div class="card">
            <div class="card-header">
                <strong>Edit Bank</strong>
            </div>
            <form method="post" action="{{route('bank.update',$bank_details->id)}}" class="form-horizontal">
                {{csrf_field()}}
                <div class="card-body">

                    <div class="form-group row">


                        <label class="col-md-3 col-form-label" for="hf-email">Bank Name <span class="asterik-red" > (*)</span></label>
                        <div class="col-md-6">
                            <input type="text"  name="bank_name" value="{{$bank_details->name}}" class="form-control" placeholder="">
                            @if($errors->has('bank_name'))
                                {!! $errors->first('bank_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-primary"> Save</button>

                </div>
            </form>
        </div>

    </div>

@stop
