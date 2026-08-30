@extends('layouts.main')
@section('title', 'Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('distributors.index')}}" title="All Distributors">Distributors</a></li>
    <li class="breadcrumb-item active">Add Payment</li>

@stop

@section('content')

    <form method="post" action="{{route('distributor.payment.store')}}">
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
                                <div class="form-group  @if($errors->has('date')) {{'has-error'}} @endif">
                                    <label>Date <span class="asterik-red" > (*)</span></label>
                                    <input type="text" name="date" class="form-control" id="dateAgent"
                                           placeholder="Enter Date" value="{{old('date')}}">
                                </div>
                                @if($errors->has('date'))
                                    {!! $errors->first('date', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif

                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('distributor_id')) {{'has-error'}} @endif">
                                    <label>Distributor <span class="asterik-red" > (*)</span></label>
                                    @if(isset($distributor))
                                        <select name="distributor_id"
                                                                class="form-control select2-state select2-hidden-accessible"
                                                                style="width: 100%;" tabindex="-1" aria-hidden="true">

                                            <option value="{{$distributor->id}}">{{$distributor->company_name}}</option>
                                        </select>
                                        <input type="hidden" name="dashboard" value="dashboard">

                                    @else
                                        <select name="distributor_id"
                                                class="form-control select2-select-distributor select2-hidden-accessible"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            @foreach(distributorList() as $key=>$value)

                                                <option value="{{$key}}">  {{$value}}
                                                </option>
                                            @endforeach
                                            @endif

                                        </select>
                                        @if($errors->has('distributor_id'))
                                            {!! $errors->first('distributor_id', '<label class="control-label text-danger"
                                                                                   for="inputError">:message</label>') !!}
                                        @endif

                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('amount')) {{'has-error'}} @endif">
                                    <label>Amount in AUD <span class="asterik-red" > (*)</span></label>
                                    <div class="input-group">
                                    @if(isset($distributor))

                                        <input type="number" name="amount" class="form-control" step="0.001"
                                               id="amount"
                                               value="{{getDistributorPayment($distributor->id) - getPaidForTransaction($distributor->id)}}"
                                               placeholder="Enter Amount">

                                    @else
                                        <input type="number" step="0.001" name="amount" class="form-control"
                                               id="amount" value="{{old('amount')}}"
                                               placeholder="Enter Amount">
                                    @endif
                                    <div class="input-group-append" style="display: contents!important;">
                                        <span class="input-group-text">@ </span>
                                        <input  style="width:60px;padding-left: 10px;" type="text" name="cost_rate" value="{{$rates->cost_rate}}">
                                    </div>
                                    </div>

                                </div>
                                @if($errors->has('amount'))
                                    {!! $errors->first('amount', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif

                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('payment_method')) {{'has-error'}} @endif">
                                    <label>Payment Method <span class="asterik-red" > (*)</span></label>
                                    <select name="payment_method"
                                            class="form-control select2-state select2-hidden-accessible"
                                            style="width: 100%;" tabindex="-1" aria-hidden="true">

                                        <option value="bank transfer"> Bank Transfer
                                        </option>
                                        <option value="cash"> Cash
                                        </option>
                                        @if($errors->has('payment_method'))
                                            {!! $errors->first('payment_method', '<label class="control-label text-danger"
                                                                                   for="inputError">:message</label>') !!}
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"
                                              id="description"
                                              placeholder="Enter Description">{{old('description')}}</textarea>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o"
                                                                         aria-hidden="true"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@section('page-script')
    <script>

        $('.select2-state').select2({
            theme:"bootstrap"
        });
        $('.select2-select-distributor').select2({
            theme:"bootstrap",
            placeholder:"Select Distributor"
        });
        var date = new Date();

        $('input[name="date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            minDate: date
        });
        $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });

        $('input[name="date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    </script>

@stop
