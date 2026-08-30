@extends('layouts.main')
@section('title', 'Add Rate')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('transactions.rates')}}" title="All Senders">Rates</a></li>
    <li class="breadcrumb-item active">Add</li>

@stop
@section('content')

    <div class="animated fadeIn">

        <div class="card">
            <div class="card-header">
                <strong>Add Rates</strong>
            </div>
            <form method="post" action="{{route('store.rate')}}" class="form-horizontal">
                {{csrf_field()}}
                <div class="card-body">

                    <div class="form-group row">


                        <label class="col-md-3 col-form-label" for="hf-email">Customer Rate <span class="asterik-red" > (*)</span></label>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="exchange_rate" class="form-control" placeholder="">
                            @if($errors->has('exchange_rate'))
                                {!! $errors->first('exchange_rate', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="hf-password">Cost Rate <span class="asterik-red" > (*)</span></label>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="cost_rate" class="form-control" placeholder="">
                            @if($errors->has('cost_rate'))
                                {!! $errors->first('cost_rate', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="hf-password">Agent Rate <span class="asterik-red" > (*)</span></label>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="agent_rate" class="form-control" placeholder="">
                            @if($errors->has('agent_rate'))
                                {!! $errors->first('agent_rate', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="hf-password">Threshold Amount <span class="asterik-red" > (*)</span></label>
                        <div class="col-md-6">
                            <input type="number" step="0.01" name="threshold_amount" class="form-control" placeholder="">
                            @if($errors->has('threshold_amount'))
                                {!! $errors->first('threshold_amount', '<label class="control-label text-danger"
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
