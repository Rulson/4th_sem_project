@extends('layouts.main')
@section('title', 'Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('distributors.index')}}" title="All Distributors">Distributors</a></li>
    <li class="breadcrumb-item active">Edit Payment</li>

@stop
@section('content')
    @include('flash::message')
            <form method="post" action="{{route('distributor.payment.update',$payments->id)}}">
            {{ csrf_field() }}
                <div class="animated fadeIn">
                    <div class="col-sm-12">

                        <div class="card">
                            <div class="card-header">
                                <strong>Payment Details</strong>
                            </div>
                            <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('date')) {{'has-error'}} @endif">
                                                <label>Date <span class="asterik-red" > (*)</span></label>
                                                <input type="text" name="date" class="form-control" id="dateAgent"
                                                       placeholder="Enter Date" value="{{$payments->date}}">
                                            </div>
                                            @if($errors->has('date'))
                                                {!! $errors->first('date', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('agent_id')) {{'has-error'}} @endif">
                                                <label>Distributor <span class="asterik-red" > (*)</span></label>

                                                    <select name="distributor_id"
                                                            class="form-control select2-state select2-hidden-accessible"
                                                            style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                        <option selected value="{{$payments->distributor_company_id}}">{{getDistributorOfficeName($payments->distributor_company_id)}}</option>
                                                    </select>
                                            </div>
                                        </div>
                                        @if($errors->has('distributor_id'))
                                            {!! $errors->first('distributor_id', '<label class="control-label text-danger"
                                                                                   for="inputError">:message</label>') !!}
                                        @endif


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('amount')) {{'has-error'}} @endif">
                                                <label>Amount in AUD <span class="asterik-red" > (*)</span></label>
                                                <div class="input-group">

                                                        <input type="number" step="0.001" name="amount" class="form-control"
                                                               id="amount" value="{{$payments->amount}}"
                                                               placeholder="Enter Amount">

                                                    <div class="input-group-append" style="display: contents!important;">
                                                        <span class="input-group-text">@ </span>
                                                        <input  style="width:60px;padding-left: 10px;" type="text" name="cost_rate" value="{{$payments->cost_rate}}">
                                                    </div>
                                                </div>

                                            </div>
                                            @if($errors->has('amount'))
                                                {!! $errors->first('amount', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Method <span class="asterik-red" > (*)</span></label>
                                                <select name="payment_method"
                                                        class="form-control select2-state select2-hidden-accessible"
                                                        style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    <option value="bank transfer"> Bank Transfer
                                                    </option>
                                                    <option value="cash"> Cash
                                                    </option>


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea  name="description" class="form-control"
                                                           id="description" placeholder="Enter Description">{{$payments->description}}</textarea>
                                            </div>
                                            {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}

                                        </div>

                                    </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        Update
                                    </button>
                    </div>
                </div>
            </form>
 @endsection
@section('page-script')
    <script>

    $('.select2-state').select2({
        theme:"bootstrap"
    });
    var date = new Date();

    $('input[name="date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });
    $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('input[name="date"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
</script>

@stop
