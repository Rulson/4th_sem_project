@extends('layouts.main')
@section('title', 'SMS')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">SMS</li>
    <li  class="breadcrumb-item active">Purchase SMS Credit</li>
@stop

@section('content')

    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Purchase SMS Credit

            </div>
            {!!Form::open(array('method' => 'post', 'class' => 'form-horizontal form-left'))!!}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group  total-amount">
                            {!!Form::label('balance', 'Current Credit Balance', array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-md-8">
                                <span class="">{{$balance}}</span>

                            </div>
                        </div>
                        <div class="form-group">
                            {!!Form::label('sms_credit', 'Buy SMS credit', array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-md-8">
                                <select class="form-control" name="sms_credit" id="sms_credit">
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="500">500</option>
                                    <option value="1000">1000</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group total-amount">
                            {!!Form::label('amount', 'Total Amount', array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-md-8">
                                $<span class="sms-amount">0</span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!!Form::label('', 'Payment Type', array('class' => 'col-sm-4 control-label')) !!}
                            <div class="col-md-8">

                                {!! Form::radio('payment_type', 'Credit Card', true, ['id' => 'Credit Card', 'class' => 'payment_type']) !!}
                                {!! Form::label('Credit Card', 'Credit Card', array('class' => 'control-label')) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="{{ env('STRIPE_PUBLISHABLE_KEY') }}"
                                    data-name="{{config('app.name')}}"
                                    data-description="Subscription"
                                    data-image=""
                                    data-locale="auto"
                                    data-zip-code="true"
                                    data-currency="aud"
                                    data-label="Purchase Now">
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
    </div>
@endsection

@section('page-script')
    <script>
    $(document).ready(function () {
    var CSRF_TOKEN = "<?php echo csrf_token() ?>";
    $('#sms_credit').change(function (e) {
    e.preventDefault();
    var sms_credit = $(this).val();
    $.post("<?php echo url('/sms/get_purchase_amount') ?>", {
    'sms_credit': sms_credit,
    _token: CSRF_TOKEN
    })
    .done(function (resp) {
    if (resp != 'false') {
    $('.sms-amount').html(resp);
    }
    })
    //                alert(sms_credit);

    });

    $('#sms_credit').trigger('change');
    });

    </script>
@stop
