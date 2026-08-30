@extends('layouts.main')
@section('title', 'Send Money')
@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Send Money</li>
@stop
@php

    foreach($senders as $key => $sender){
            $sender_id = $sender->sender_id;
    }

    $sender = \App\Modules\Sender\Models\Sender::find($sender_id);
@endphp
@section('content')

    <style>
        .bg-card-color1 {
            background-color: #f5f5f5;
        }

        .border-color1 {
            border-color: #dcdcdc !important;
        }
        #uploadTitle{
            font-size: 17px;
        }
    </style>
    <div class="modal fade sendingmodal" id="sendmoney-modal" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    @if($sender->sender_status_id == 5)
    <div class="alert alert-warning" role="alert">
        <h4>Alert!</h4>

        <p>Please wait until we verify your identification detail for transaction.</p>
    </div>
    @endif
    @include('flash::message')
    {!!Form::open(array('route' => ['transaction.post.sendmoney'],'files'=>'true','id'=>'submit'))!!}
    <div class="col-xs-12 firstpage">
        <span class="notify"></span>
        <div class="card">

            <input type="hidden" name="sender_id" value="{{$sender_id}}">
            <input type="hidden" name="staff_notes">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group @if($errors->has('beneficiary_id')) {{'has-error'}} @endif"
                                     id="beneficiary_div">
                                    <label>SELECT BENEFICIARY:</label>

                                    <div class="input-group my-colorpicker2 colorpicker-element">
                                        <select class="form-control selectBeneficiary select2 " id="beneficiary-id"
                                                name="beneficiary_id">
                                            <option></option>



                                        </select>

                                        <div class="input-group-addon">
                                            <a type="button" data-toggle="modal" data-target="#sendmoney-modal"
                                               data-url="" id="add_ben" class="btn btn-primary ben_button btn-sm mt-1" data-backdrop="static" data-keyboard="false"
                                               disabled><i
                                                        class="fa fa-user">
                                                    Add new beneficiary</i></a>
                                        </div>
                                    </div>
                                    <span style="color: red;" id="beneficiary_select_msg"></span>
                                    <!-- /.input group -->
                                </div>
                            @if($errors->has('beneficiary_id'))
                                {!! $errors->first('beneficiary_id', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif


                            <!-- /.form-group -->

                                <div id="beneficiaryInformation" style="display: none;" class="box box-solid">
                                          </div>
                            </div>
                            <!-- /.col -->
                        </div>

                    </div>

                    <div class="col-md-6">
                        <fieldset class="form-group @if($errors->has('sending_amount')) {{'has-error'}} @endif"
                                  id="sending_amount_div">
                            <label>Sending Amount<span class="asterik-red" > (*)</span></label>
                            <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">AUD</span>
                        </span>
                                <input type="number" min="1" step="0.01" class="form-control"
                                       value="{{old('sending_amount')}}" name="sending_amount"
                                       id="sending_amount"
                                       placeholder="Enter Sending Amount">
                            </div>
                            @if($errors->has('sending_amount'))
                                {!! $errors->first('sending_amount', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                            <label class="control-label text-danger d-none" id="limit-exceeded-warning"
                                   for="inputError" style="margin-top: 10px">
                                <span>
                                    You can only send up to AUD {{number_format($remainingLimitAmount)}}   today.
                                </span>
                                <br>
                                <span>
                                    Your sending amount exceeds the daily limit of AUD {{number_format($totalLimitAmount)}}. Please contact admin to send above the limit.
                                </span>
                                <br>
                                <span id="sent-today-text">
                                    Total sent today: AUD {{number_format($totalAmountSentToday)}}
                                </span>
                                <br>
                            </label>
                        </fieldset>

                        <div class="form-group @if($errors->has('exchange_rate')) {{'has-error'}} @endif">
                            <label>Exchange Rate<span class="asterik-red" > (*)</span></label>

                                <input type="text" class="form-control" {{--value="{{$exchangerate->exchange_rate}}"--}}
                                       name="exchange_rate" id="exchangeRate"
                                       placeholder="Enter Exchange Rate" readonly>


                        </div>
                        @if($errors->has('exchange_rate'))
                            {!! $errors->first('exchange_rate', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif

                        <fieldset class="form-group @if($errors->has('payment_amount')) {{'has-error'}} @endif"
                                  id="payment_amount_div">
                            <label>Payment Amount<span class="asterik-red" > (*)</span></label>
                            <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">NPR</span>
                        </span>
                                <input type="number" class="form-control" step="0.01" name="payment_amount"
                                       value="{{old('payment_amount')}}" id="paymentAmount" readonly
                                       placeholder="Enter Payment Amount">
                            </div>
                            @if($errors->has('payment_amount'))
                                {!! $errors->first('payment_amount', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </fieldset>

                        @if(isset($referral)  && $referral != false)
                            <div class="form-group ">
                                <label>Use {{$usable_points}} referral points (Total : {{$referral_points}} points)&nbsp;</label>
                                {!! Form::checkbox('referral_discount', 1, false,array('class' => '','id'=>'referral_discount')) !!}
                            </div>
                        @endif
                        <div class="form-group @if($errors->has('service_charge')) {{'has-error'}} @endif"
                             id="serviceCharge1_div">
                            <label>Service Charge
                                @if($can_use_free_service_charge)
                                    <span class="text-success"> (Free service charge from referral)</span>
                                @endif
                                <span class="asterik-red" > (*)</span></label>
                            <div class="input-group"
                                 style="width: 30%;">
                                <span class="input-group-prepend">
                                <span class="input-group-text">AUD</span>
                                </span>

                                    <input style="height: 35px;" type="number"
                                           min="1" step="0.001"
                                           class="form-control"
                                           value="{{$serviceCharge}}"
                                           name="service_charge" readonly
                                           id="serviceCharg">

                            </div>
                            @if($errors->has('service_charge'))
                                {!! $errors->first('service_charge', '<label class="control-label text-danger"
                                                                        for="inputError">:message</label>') !!}
                            @endif
                        </div>

                        <div class="form-group @if($errors->has('coupon_code')) {{'has-error'}} @endif">
                            <label>Coupon Code</label>

                            <input type="text" class="form-control"
                            @if($can_use_free_service_charge)
                                disabled
                            @endif

                            name="coupon_code" id="coupon_code"
                                   placeholder="Enter Coupon Code">


                        </div>
                        @if($errors->has('coupon_code'))
                            {!! $errors->first('coupon_code', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                        <div class="form-group">
                            <label>Payment Type In Nepal <span class="asterik-red" > (*)</span></label>
                            <select class="form-control" name="payment_type" id="payment_type">
                            {{--    <option value="">Please Select</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                 <option value="Local Remit">Local Remit</option>--}}
                            </select>
                        </div>
                        @if($errors->has('payment_type'))
                            {!! $errors->first('payment_type', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif


                        <div class="form-group" style="display: none;">
                            <label>Pickup District <span class="asterik-red" > (*)</span></label>
                            <select class="form-control" name="pickup_district" id="district_name"></select>
                        </div>



                            <div class="form-group">
                                <label>Receipt <span class="asterik-red" > (*)</span></label>
                                <div class="box-header with-border btn-warning">
                                    <h3 class="box-title"><div
                                                id="uploadTitle"></div>
                                    </h3>
                                </div>
                                <div>
                                    {!! $application->bank_notes !!}
                                </div>

                                <div class="input-group control-group increment @if($errors->has('receipt')) {{'has-error'}} @endif"
                                     id="receipt_div">
                                    <input type="file" name="receipt[]" class="form-control"
                                           accept="image/jpeg , image/jpg, image/gif, image/png ,application/pdf"
                                           id="receipt">
                                    <div class="input-group-btn">
                                        <button class="btn btn-success btn-success-receipt" type="button"><i
                                                    class="glyphicon glyphicon-plus"></i>Add
                                        </button>
                                    </div>

                                </div>

                                <div class="clone hide" style="display:none;">
                                    <div class="control-group input-group" style="margin-top:10px">
                                        <input type="file" name="receipt[]" class="form-control"
                                               accept="image/jpeg , image/jpg, image/gif, image/png,application/pdf">
                                        <div class="input-group-btn">
                                            <button class="btn btn-danger btn-receipt-danger" type="button">
                                                <i class="glyphicon glyphicon-remove"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if($errors->has('receipt'))
                                    {!! $errors->first('receipt', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>


                        <label>Email Order Confirmation &nbsp;</label>
                        {!! Form::checkbox('email_confirmation', 1, true,array('class' => '')) !!}


                            @if($sender->sender_status_id == 5)
                                <input type="button"  value="Submit" class="btn btn-primary pull-right"
                                       style="margin-left: 2px;" disabled>
                                @else
                            <input type="button" id="submit1" value="Submit" class="btn btn-primary pull-right"
                                   style="margin-left: 2px;" onclick="validate()">
                            @endif


                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    {!!Form::close()!!}



@stop

@section('page-script')
    <script type="text/javascript">
        var senderId = "{{$sender_id}}";
        var beneficiaryId;

        $(document).ready(function () {
            @if(isset($referral) && $referral != false)
            $("#referral_discount").change(function() {


                if(this.checked) {

                    calcReferral();
                }else{
                    if($("#coupon_code").val() != ""){
                        validateCoupon();
                    }
                    $('input[name="service_charge"]').val("{{$serviceCharge}}");
                }
                $("input[name='payment_amount']").trigger('keyup');
            });
            @endif

            var url = "{{route('transaction.beneficiary.add',":id")}}".replace(':id', 'id=' + senderId);
            $('#add_ben').attr('data-url', url);

        });


        const beneficiaryOldValue = '{{ old('beneficiary_id') }}';
        if(beneficiaryOldValue != ''){
            beneficiaryId = beneficiaryOldValue;
            fetchBeneficiaries(beneficiaryOldValue);
            fetchBeneficiaryData();
        }
        else{
            $('#add_ben').removeAttr('disabled');
            fetchBeneficiaries();
        }

        var typingTimer;                //timer identifier
        var doneTypingInterval = 500;  //time in ms, 5 second for example
        var $input = $('#coupon_code');

        //on keyup, start the countdown
        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown
        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        //user is "finished typing," do something
        function doneTyping () {
            validateCoupon();
        }
        @if(isset($referral)  && $referral != false)
        function calcReferral(){
            var point_rate = "{{$points_rate}}";
            var usable_points = "{{$usable_points}}";
            var equivalent_service_charge = parseFloat(usable_points/point_rate);

            if({{$serviceCharge}}-equivalent_service_charge > 0)
                $('input[name="service_charge"]').val(({{$serviceCharge}}-equivalent_service_charge).toFixed(2));
            else
                $('input[name="service_charge"]').val(0);

            $("#coupon_code").val('');
        }
        @endif

        function validateCoupon(){
            $('.error').remove();
            var code = $("#coupon_code").val();
                data = new FormData();
                data.append('code', code);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                    },
                    type: 'POST',
                    url: "{{route('coupon.verify')}}",
                    data: data,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    encode: true
                }).done(function (result) {
                    if(result.status != 200) {
                        $('input[name="service_charge"]').val({{$serviceCharge}});
                        $('#submit').find('#coupon_code').after('<label class="error ">' + result.message + '</label>').closest('.form-group').addClass('has-error');
                    } else {
                        if(result.response.discount_unit == 'f')
                            discountval = result.response.discount_value;
                        else
                            discountval = ("{{$serviceCharge}}" * result.response.discount_value)/100;
                        if({{$serviceCharge}}-discountval > 0)
                            $('input[name="service_charge"]').val({{$serviceCharge}}-discountval);
                        else
                            $('input[name="service_charge"]').val(0);
                    }


                    @if(isset($referral) && $referral != false)
                    if($("#referral_discount").is(":checked")){
                        calcReferral();
                    }
                    @endif
                    $("input[name='payment_amount']").trigger('keyup');
                });
        }
        function validate() {

                if($("#coupon_code").val() != "" && $("#referral_discount").is(":checked")){
                    alert('Cannot have both referral discount and coupon discount  at the same time');
                    return false;
                }
            var r = document.getElementById('receipt');

                if ($("#receipt").val() === '') {
                    alert('receipt value is empty');
                    $("#uploadTitle").css("color", "red");

                    return false;
                }

                else if (r.files[0].size / 1024 / 1024 > 10) {
                    alert('File size exceeds 10 MB');
                    $("#receipt_div").css("color", "black");

                }
                else {
                    $("#receipt_div").removeClass("has-error");
                    document.getElementById("submit").submit();
                    document.getElementById("submit1").disabled = true;
                    document.getElementById("submit1").value = 'Submitting.Please wait...';
                     }

            }
        $('#payment_type').change(function () {
            fetchDistricts();
        });

        function fetchDistricts() {
            if ($('#payment_type').val() == 'Local Remit') {
                const data = ['Jhapa', 'Ilam', 'Panchthar', 'Taplejung', 'Morang', 'Sunsari', 'Bhojpur', 'Dhankuta', 'Terhathum', 'Sankhuwasabha',
                    'Saptari', 'Siraha', 'Udayapur', 'Khotang', 'Okhaldhunga', 'Solukhumbu', 'Dhanusa', 'Mahottari', 'Sarlahi', 'Sindhuli', 'Surkhet',
                    'Ramechhap', 'Dolakha', 'Bhaktapur', 'Dhading', 'Kathmandu', 'Kavrepalanchok', 'Lalitpur', 'Nuwakot', 'Rasuwa', 'Nawalparasi',
                    'Sindhupalchok', 'Bara', 'Parsa', 'Rautahat', 'Chitwan', 'Makwanpur', 'Gorkha', 'Kaski', 'Lamjung', 'Syangja', 'Tanahu',
                    'Manang', 'Kapilvastu', 'Nawalpur', 'Parasi', 'Rupandehi', 'Arghakhanchi', 'Gulmi', 'Palpa', 'Baglung', 'Myagdi', 'Parbat',
                    'Mustang', 'Dang', 'Pyuthan', 'Rolpa', 'Eastern Rukum', 'Western Rukum', 'Salyan', 'Dolpa', 'Humla', 'Jumla', 'Kalikot', 'Mugu', 'Banke',
                    'Bardiya', 'Dailekh', 'Jajarkot', 'Kailali', 'Achham', 'Doti', 'Bajhang', 'Bajura', 'Kanchanpur', 'Dadeldhura', 'Baitadi', 'Darchula'];
                var districts = [];
                districts.push('<option></option><br>');
                data.sort().forEach(function (arrayItem) {
                    districts.push('<option value="' + arrayItem + '">' + arrayItem + '</option>');
                });
                $('#district_name').html(districts);
                $('#district_name').closest('.form-group').show();

            } else {
                $('#district_name').closest('.form-group').hide();
                $('#district_name').val('');
            }

        }



            function fetchBeneficiaries(beneficiary_id='') {
                $("#beneficiaryInformation").html('<img class="mt-3 mb-3 pl-1" src="{{ asset('assets/img/loader.gif') }}"><br><br>');
                $.ajax({
                    url: 'fetch-beneficiaries',
                    method: 'GET',
                    data: {Id: senderId},
                    dataType: 'json',
                    success: function (data) {
                        var beneficiary = [];
                        beneficiary.push('<option></option><br>');
                        data.forEach(function (arrayItem) {
                            if(beneficiary_id != ''  && beneficiary_id == arrayItem.beneficiary_id){
                                beneficiary.push('<option selected value="' + arrayItem.beneficiary_id + '" data-phone-number="' + arrayItem.number + '" data-address="' + arrayItem.street + '" data-bank-name="' + arrayItem.bankName + '" data-account-no="' + arrayItem.accountNo + '">' + arrayItem.full_name + '</option><br>');

                            }
                            else{
                                beneficiary.push('<option value="' + arrayItem.beneficiary_id + '" data-phone-number="' + arrayItem.number + '" data-address="' + arrayItem.street + '" data-bank-name="' + arrayItem.bankName + '" data-account-no="' + arrayItem.accountNo + '">' + arrayItem.full_name + '</option><br>');

                            }
                        });

                        $('#beneficiary-id').html(beneficiary);
                        if(beneficiary_id != ''){
                            $('#beneficiaryInformation').css('display', 'block');
                            beneficiaryId=beneficiary_id;
                        }else{

                            $('#beneficiaryInformation').css('display', 'none');
                        }
                    }
                });
            }

            $('.selectBeneficiary').change(function () {
                $('#beneficiaryInformation').css('display', 'block');
                beneficiaryId = $(this).find('option:selected').val();
                fetchBeneficiaryData();
            });

            function fetchBeneficiaryData() {
                $("#beneficiaryInformation").html('<img class="mt-3 mb-3 pl-1" src="{{ asset('assets/img/loader.gif') }}"><br><br>');
                $.ajax({
                    url: 'fetch-beneficiary-data',
                    method: 'GET',
                    data: {Id: beneficiaryId},
                    dataType: 'json',
                    success: function (data) {
                        var url = "{{route('transaction.beneficiary.edit',":id")}}".replace(':id', data.beneficiary_id);
                        var url1 = "{{route('change.account',":id")}}".replace(':id', data.beneficiary_id);


                        $("#beneficiaryInformation").html(
                            " <div class=\"box-header with-border bg-card-color1 border-color1 \">\n" + "\n" +
                            "<span class=\"username\" style=\"color:black;\"><span class=\"info-box-number\">" + data.full_name + ' (' + data.formattedId + ')' + "</span>\n" + "</span>\n" + "<div class=\"box-tools pull-right\" style=\"top: 10px;\">\n" +
                            "</div>\n" + "</div>\n" + "<div class=\"box-body\" style=\"padding: 0;\">\n" +
                            "<div class=\"info-box bg-card-color1\" style=\"margin-bottom: 0\">\n" + "\n" + "\n" + "<div class=\"info-box-content\" style=\"margin-left: 0\">\n" + "<span class=\"\">\n"
                            + "<div class=\"row\">\n" + "<div class=\"col-md-12\">\n" + data.number + "<br>\n" + data.street + " " + data.suburb + " " + data.state + " " + data.country + "</div>\n" + "<div style=\"border-top: 1px solid #f4f4f4;margin: 4px 0\"></div>\n"
                            + "</div>\n" + "<div class=\"row\">\n" + "<div class=\"col-md-12\">\n" + "<div style=\"border-top: 1px solid #dcdcdc;margin: 4px 0\"></div>\n" + data.accountName + " | ACCOUNT NO: " + data.accountNo + " <br> BRANCH: " + data.bsb + " | " + data.bankName + " \n" + "</div>\n" + "</div>\n" + "</span>\n" +
                            "</div>\n" + "</div>\n" + "</div>\n" + "<div class=\"box-footer border-color1  bg-card-color1 text-right\">\n" + "Added By: " + data.added_by + "\n" + "</div>"
                        );
                    }
                });


            }

        function copyToClipboard() {
            // Get the value of the hidden input field
            const copyText = '{{$application->pay_id}}';

            // Use the Clipboard API to copy the value
            navigator.clipboard.writeText(copyText)
                .then(() => {
                    alert("PayID copied to clipboard! \n ");
                })
                .catch(err => {
                    console.error("Failed to copy the link: ", err);
                });
        }

            $("input[name='sending_amount'],input[name='exchange_rate']").on('keyup', function () {

                sendingAmt = parseFloat($("input[name='sending_amount']").val());
                exchangeAmt =  getExchangeRateBySendingAmount(sendingAmt);

                paymentAmt = sendingAmt * exchangeAmt;
                $("input[name='payment_amount']").val(paymentAmt.toFixed(2));
                $("#sendingamount").text('AUD' + ' ' + sendingAmt.toFixed(2));

                totalPayment = sendingAmt + parseFloat("{{$serviceCharge}}");

                $("#uploadTitle").html('You are requested to upload receipt with AUD. ' + totalPayment.toFixed(2) + ' on following detail: <br>' +
                    @if($application->pay_id)
                    'For quick settlements under $2K, please use PayID. <br>' +
                    '<img style="height:60px;width:auto;margin-left:-10px;" src="{{asset('assets/images/pay_id.svg')}}" > {{$application->pay_id}}' + '' +
                    ' <button type="button" class="btn btn-warning" onclick="copyToClipboard()">  Copy <i class="fa fa-copy"></i> </button> <br> ' +
                    'Alternative Payment option: <br> ' +
                    @endif
                    ' Account Name: {{ $application->bank_account_name }} <br>' +
                    'BSB: {{ $application->bank_bsb }} <br> ' +
                    'A/C: {{ $application->bank_account_number }} <br> ' +
                    'Bank: {{ $application->bank_name }} <br> ');
                $("#uploadTitle").css("padding", "7px");
            });
            $("input[name='payment_amount']").on('keyup', function () {

                paymentAmt = parseFloat($("input[name='payment_amount']").val());
                sendingAmt = parseFloat($("input[name='sending_amount']").val());
                exchangeAmt =  getExchangeRateBySendingAmount(sendingAmt);

                $("input[name='sending_amount']").val(sendingAmt.toFixed(2));
                totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());

                $("#uploadTitle").html('You are requested to upload receipt with AUD. ' + totalPayment.toFixed(2) + ' on following detail: <br>' +
                    'For quick settlements under $2K, please use PayID. <br>' +
                    '<img style="height:60px;width:auto;margin-left:-10px;" src="{{asset('assets/images/pay_id.svg')}}" > {{$application->pay_id}}' + '' +
                    ' <button type="button" class="btn btn-warning" onclick="copyToClipboard()">  Copy <i class="fa fa-copy"></i> </button> <br> ' +
                    'Alternative Payment option: <br> ' +
                    ' Account Name: {{ $application->bank_account_name }} <br>' +
                    'BSB: {{ $application->bank_bsb }} <br> ' +
                    'A/C: {{ $application->bank_account_number }} <br> ' +
                    'Bank: {{ $application->bank_name }} <br> ');
                $("#uploadTitle").css("padding", "7px");
            });


            // process the beneficiary form
            $(document).on("submit", "#add-beneficiary", function (event) {
                var formData = $(this).serialize();
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();
                $("#add-beneficiaryPopUp").html('Submitting...');
                $("#add-beneficiaryPopUp").attr('disabled', true);

// process the form
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    encode: true
                })
                    .done(function (result) {
                        if (result.status == 1) {
                            var beneficiary = $('#beneficiary-id');
                            beneficiary.append($('<option value="' + result.data.beneficiary_id + '" data-phone-number="' + result.data.number + '" data-phone-address="' + result.data.address + '">' + result.data.fullname + '</option>'));
                            beneficiary.val(result.data.beneficiary_id).change(); //Necessary for beneficiary2

                            $('.modal').modal('hide');
                            toastr.success('Beneficiary has been added successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //  $('.box-primary').before(notify('success', 'Beneficiary Added Successfully!'));
                        } else {
                            $.each(result.data.errors, function (i, v) {
                                $('#add-beneficiary').find('#' + i).after('<label class="error ">' + v + '</label>').closest('.form-group').addClass('has-error');
                                $("#add-beneficiaryPopUp").attr('disabled', false);
                                $("#add-beneficiaryPopUp").html('Save');
                            });
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
            });




            function notify(type, text) {
                return '<div class="callout callout-' + type + '"><p>' + text + '</p></div>';
            }

            function formatStateBeneficiary(state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<div id=' + '<br>' + state.text + ' | ' + state.element.getAttribute('data-phone-number') + ' | ' + state.element.getAttribute('data-address') + '<br>' + state.element.getAttribute('data-bank-name') + ' | ' + state.element.getAttribute('data-account-no') + '</div>'
                );
                return $state;
            }

            function matchCustomBeneficiary(params, data) {
// If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

// Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined' || data.element.attributes.getNamedItem('data-phone-number') == null) {
                    return null;
                }

// `params.term` should be the term that is used for searching
// `data.text` is the text that is displayed for the data object
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1 || data.element.attributes.getNamedItem('data-address').value.toLowerCase().indexOf(params.term.toLowerCase()) > -1 || data.element.attributes.getNamedItem('data-phone-number').value.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    modifiedData.text += ' (matched)';

// You can return modified objects from here
// This includes matching the `children` how you want in nested data sets
                    return modifiedData;
                }

// Return `null` if the term should not be displayed
                return null;
            }

            $(function () {
//Initialize Select2 Elements
                $


                $('.selectBeneficiary').select2(
                    {
                        theme: "bootstrap",
                        templateResult: formatStateBeneficiary,
                        matcher: matchCustomBeneficiary,
                        placeholder: 'Choose a beneficiary'
                    }
                );

                $('.select2-transaction-type').select2();
                $('.select2-added-by').select2();
                $('.select2-multiple-transaction-status').select2();
                /*$('.select2-state').select2();
                $('.select2-change-stage').select2();*/
                $('.select2-1').select2();

                $(".btn-success-receipt").click(function () {
                    var html = $(".clone").html();
                    $(".increment").after(html);
                });

                $("body").on("click", ".btn-receipt-danger", function () {
                    $(this).parents(".control-group").remove();
                });

            });
            $(document).ready(function () {
                $("#add_ben").on("click", function () {


                    var a = $("#add_ben").attr('data-url');
                    if (a == '') {

                        $('#add_ben').attr('data-toggle', "");
                        toastr.error('Please select a sender first!', {
                            closeButton: true,
                            progressBar: true,
                        });
                        window.location.reload();

                    }
                });
            });

        $(document).ready(function(){
            var min_bank_transfer = 0;
            var max_bank_transfer = 0;
            var min_local_remit = 0;
            var max_local_remit = 0;
            @if(isset($payment_types['bank_transfer']))
                min_bank_transfer = "{!! $payment_types['bank_transfer']['min'] !!}";
            max_bank_transfer = "{!! $payment_types['bank_transfer']['max'] !!}";
            min_local_remit = "{!! $payment_types['local_remit']['min'] !!}";
            max_local_remit = "{!! $payment_types['local_remit']['max'] !!}";

            @endif

            addPaymentDropDown(0,Number(min_bank_transfer),Number(max_bank_transfer),Number(min_local_remit),Number(max_local_remit));

            $('body').on('blur',"#sending_amount",function () {
                var paymentAmountValue = $("#paymentAmount").val();
                addPaymentDropDown(Number(parseFloat(paymentAmountValue).toFixed(2)), Number(parseFloat(min_bank_transfer).toFixed(2)), Number(parseFloat(max_bank_transfer).toFixed(2)), Number(parseFloat(min_local_remit).toFixed(2)), Number(parseFloat(max_local_remit).toFixed(2)));

            });
        });

        function addPaymentDropDown(payment_amount, min_bank_transfer, max_bank_transfer, min_local_remit, max_local_remit){
            var option = '<option value=>Please Select</option>';

            var bank_option = '<option value="Bank Transfer">Bank Transfer</option>';
            var remit_option = '<option value="Local Remit">Local Remit</option>';

            option += bank_option;

            // if(min_bank_transfer == 0 && max_bank_transfer == 0 && min_local_remit == 0 && max_local_remit == 0  ){
            //     option += bank_option;
            //     option += remit_option;
            // }else {
            //     if (payment_amount != 0 && (payment_amount >= min_local_remit || min_local_remit == 0) && (payment_amount <= max_local_remit || max_local_remit == 0)) {
            //         option += remit_option;
            //     }
            //
            //     if (payment_amount != 0 && (payment_amount >= min_bank_transfer || min_bank_transfer == 0) && (payment_amount <= max_bank_transfer || max_bank_transfer == 0)) {
            //         option += bank_option;
            //     }
            // }
            $("#payment_type").html(option);

        }


        function getExchangeRateBySendingAmount(sendingAmount){
            var test_amount = parseFloat(sendingAmount).toFixed(2);

            var new_exchange_rate;
            var new_exchange = {!! json_encode($exchangerate1) !!};
            $.each(new_exchange, function (key, val) {

                var key_amount = parseFloat(key).toFixed(2);
                new_exchange_rate = val; //smallest rate if difference is negative
                var diff = test_amount - key_amount;
                if(diff >= 0) {
                    new_exchange_rate = val;
                    return false;
                }

            });
            $("input[name='exchange_rate']").val(new_exchange_rate);
            $("input[name='exchange_rate']").trigger('change');
            return new_exchange_rate;
        }

        $(document).ready(function(){
            getExchangeRateBySendingAmount(0);
        })
        // max limit logic
        @if($sender->is_restricted)
        $(document).ready(function(){
            sendingAmtInput = $("input[name='sending_amount']").on('keyup',function(){
                if(sendingAmtInput.val() > {{$remainingLimitAmount}}){
                    $("#submit1").prop('disabled',true)
                    $("remaining-limit-text").text("Remaining: ${{$remainingLimitAmount}}")
                    $('#limit-exceeded-warning').removeClass('d-none');
                }else{
                    $("#submit1").prop('disabled',false)
                    $('#limit-exceeded-warning').addClass('d-none');
                }
            });
        })
        @endif
    </script>
@endsection
