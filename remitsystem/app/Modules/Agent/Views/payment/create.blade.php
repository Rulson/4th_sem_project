@extends('layouts.main')
@section('title', 'Agent')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('agents.index')}}" title="All Agents">Agents</a></li>
    <li class="breadcrumb-item active">Add Payment</li>

@stop
@section('content')
    @include('flash::message')
            <form method="post" action="{{route('agent.payment.store')}}">
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
                                                <label>Date<span class="asterik-red" > (*)</span></label>
                                                <input type="text" name="date" class="form-control" id="dateAgent"
                                                       placeholder="Enter Date" value="{{old('date')}}">
                                            </div>
                                            @if($errors->has('date'))
                                                {!! $errors->first('date', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('agent_id')) {{'has-error'}} @endif">
                                                <label>Agent<span class="asterik-red" > (*)</span></label>
                                                @if(isset($agent))
                                                    <select name="agent_id"
                                                            class="form-control select2-state select2-hidden-accessible"
                                                            style="width: 100%;" tabindex="-1" aria-hidden="true">
<option
                                                        <option selected value="{{$agent->id}}">{{getAgentName($agent->id)}}</option>
                                                    </select>
                                                    <input type="hidden" name="dashboard" value="dashboard">
                                                @else
                                                <select name="agent_id"
                                                        class="form-control select2-select-customer select2-hidden-accessible "
                                                        style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    @foreach(agentList() as $key=>$value)
<option></option>
                                                        <option value="{{$key}}">  {{$value}}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                    @endif
                                            </div>
                                            @if($errors->has('agent_id'))
                                                {!! $errors->first('agent_id', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if($errors->has('amount')) {{'has-error'}} @endif">
                                                <label>Amount<span class="asterik-red" > (*)</span></label>
                                                @if(isset($agent))
                                                    <input type="number"  name="amount" class="form-control" step="0.001"
                                                           id="amount" value="{{getAgentCommission($agent->id) - getAgentPayment($agent->id)}}"
                                                           placeholder="Enter Amount">

                                                @else
                                                <input type="number" step="0.001" name="amount" class="form-control"
                                                       id="amount" value="{{old('amount')}}"
                                                       placeholder="Enter Amount">
                                                    @endif
                                            </div>
                                            @if($errors->has('amount'))
                                                {!! $errors->first('amount', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Method<span class="asterik-red" > (*)</span></label>
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
                                                           id="description" placeholder="Enter Description">{{old('description')}}</textarea>
                                            </div>
                                            {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}

                                        </div>

                                    </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        Save
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
    $('.select2-select-customer').select2({
        theme:"bootstrap",
        placeholder:"Select Agent"
    });

    // $('input[name="date"]').daterangepicker({ dateFormat: 'DD/MM/YYYY' }).val();
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
