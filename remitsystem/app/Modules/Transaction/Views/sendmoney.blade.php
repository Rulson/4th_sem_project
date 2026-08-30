@extends('layouts.main')
@section('title', 'Send Money')
@section('breadcrumb')
    @parent

    <li class="breadcrumb-item active">Send Money</li>
@stop
@php

    $account_name = $serviceCharge1->account_name;
    $account_no = $serviceCharge1->account_no;
    $bsb = $serviceCharge1->bsb;
    $bank_name = $serviceCharge1->bank_name;
   // $application = \App\Modules\Application\Models\Application::where('domain_url',request()->getHttpHost())->first();

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
    @include('flash::message')
    {!!Form::open(array('route' => ['transaction.post.sendmoney'],'files'=>'true','id'=>'submit'))!!}
    <div class="col-xs-12 firstpage">
        <span class="notify"></span>
        <div class="card">
            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                <div class="card-header">

                    <div class="row">
                        {!!Form::select('agent_id', agentList(), null, array('class' => 'form-control col-md-3 select2','placeholder'=>'Choose Agent', 'id'=>'agents'))!!}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!!Form::select('distributor_id', distributorList(), null, array('class' => 'form-control col-md-3 select2','placeholder'=>'Choose Distributor', 'id'=>'distributors'))!!}
                    </div>

                </div>
            @endif


            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3)
                                <div class="col-md-12">
                                    <div class="form-group @if($errors->has('sender_id')) {{'has-error'}} @endif"
                                         id="sender_div">
                                        <label>SELECT SENDER:</label>

                                        <div class="input-group my-colorpicker2 colorpicker-element">
                                            <select class="form-control sender-select2 select2" id="sender"
                                                    name="sender_id">

                                                <option>
                                                </option>
                                            </select>

                                            <div class="input-group-addon">

                                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3)
                                                    <a type="button" data-toggle="modal"
                                                       data-url="{{route('transaction.sender.add')}}"
                                                       data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                                                       class="btn btn-primary btn-sm mt-1"><i class="fa fa-user">
                                                            Add new sender</i></a>

                                                @endif
                                            </div>
                                        </div>
                                        <span style="color: red;" id="sender_select_msg"></span>
                                        <!-- /.input group -->
                                    </div>
                                @if($errors->has('sender_id'))
                                    {!! $errors->first('sender_id', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif

                                <!-- /.form-group -->

                                    <div id="senderInformation" style="display: none;"
                                         class="box bg-card-color1 box-solid">

                                        <!-- /.box-footer -->
                                    </div>

                                </div>
                            @else

                                <div class="col-md-12" style="display: none;">
                                    <div class="form-group @if($errors->has('sender_id')) {{'has-error'}} @endif"
                                         id="sender_div">

                                        <div class="input-group my-colorpicker2 colorpicker-element">
                                            <select class="form-control select2" id="sender" name="sender_id">
                                                @foreach($senders as $key => $sender)
                                                    <option senderData="{{$sender}}" value="{{$sender->sender_id}}"
                                                            data-status="{{ strtolower($sender->status) }}"
                                                            data-phone-number="{{ $sender->number }}"
                                                            data-email="{{ $sender->email }}"
                                                            selected>{{ $sender->full_name}}
                                                    </option>
                                                @endforeach

                                            </select>

                                            <div class="input-group-addon">

                                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3)
                                                    <a type="button" data-toggle="modal"
                                                       data-url="{{route('transaction.sender.add')}}"
                                                       data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                                                       class="btn btn-primary  btn-sm mt-1"><i class="fa fa-user">
                                                            Add new sender</i></a>

                                                @endif
                                            </div>
                                        </div>
                                        <span style="color: red;" id="sender_select_msg"></span>
                                        <!-- /.input group -->
                                    </div>
                                @if($errors->has('sender_id'))
                                    {!! $errors->first('sender_id', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif

                                <!-- /.form-group -->

                                    <div id="senderInformation" style="display: none;"
                                         class="box bg-card-color1 box-solid">

                                        <!-- /.box-footer -->
                                    </div>

                                </div>

                        @endif
                        <!-- /.col -->
                        </div>
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
                            @if(Auth::user()->level_id == 3)
                            <label class="control-label text-danger d-none" id="limit-exceeded-warning"
                                   for="inputError" style="margin-top: 10px">
                                <span>
                                    You can only send up to AUD {{number_format($remainingLimitAmount)}} today.
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
                           @endif
                        </fieldset>

                        <div class="form-group @if($errors->has('exchange_rate')) {{'has-error'}} @endif">
                                <label>Exchange Rate<span class="asterik-red" > (*)</span></label>
                            @if(Auth::user()->level_id==1 || Auth::user()->level_id==2 || Auth::user()->level_id==3)
                                <input type="text" class="form-control" value="{{$exchangerate->exchange_rate}}"
                                       name="exchange_rate" id="exchangeRate"
                                       placeholder="Enter Exchange Rate">
                            @endif
                            @if(Auth::user()->level_id==5)
                                <input type="text" class="form-control" value="{{$exchangerate->exchange_rate}}"
                                       name="exchange_rate" id="exchangeRate"
                                       placeholder="Enter Exchange Rate" readonly>

                            @endif

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
                                       value="{{old('payment_amount')}}" id="paymentAmount"
                                       placeholder="Enter Payment Amount" readonly>
                            </div>
                            @if($errors->has('payment_amount'))
                                {!! $errors->first('payment_amount', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </fieldset>
                        <div class="form-group @if($errors->has('service_charge')) {{'has-error'}} @endif"
                             id="serviceCharge1_div">
                            <label>Service Charge<span class="asterik-red" > (*)</span></label>
                            <div class="input-group"
                                 style="width: 30%;">
                                <span class="input-group-prepend">
                                <span class="input-group-text">AUD</span>
                                </span>
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 ||Auth::user()->level_id == 3)

                                    <input style="height: 35px;" type="number"
                                           min="1" step="0.001"
                                           class="form-control serv"
                                           value="{{$serviceCharge}}"
                                           name="service_charge"
                                           id="serviceChargField">
                                @else
                                    <input style="height: 35px;" type="number"
                                           min="1" step="0.001"
                                           class="form-control"
                                           value="{{$serviceCharge}}"
                                           name="service_charge" readonly
                                           id="serviceCharg">
                                @endif
                            </div>
                            @if($errors->has('service_charge'))
                                {!! $errors->first('service_charge', '<label class="control-label text-danger"
                                                                        for="inputError">:message</label>') !!}
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Payment Type In Nepal <span class="asterik-red" > (*)</span></label>
                            <select class="form-control" name="payment_type" id="payment_type">
                                {{--<option value="">Please Select</option>--}}
                                {{--<option value="Bank Transfer">Bank Transfer</option>--}}
                                 {{--<option value="Local Remit">Local Remit</option>--}}
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
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id ==3)
                            <div class="form-group @if($errors->has('quick_notes')) {{'has-error'}} @endif">
                            <label>Quick Note <span class="asterik-red" > (*)</span></label>
                            <input type="text" class="form-control" name="quick_notes" id="admin_staff_notes"
                                   value="{{old('quick_notes')}}"
                                   placeholder="Quick Note">

                        </div>
                        @if($errors->has('quick_notes'))
                            {!! $errors->first('quick_notes', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                        @endif
                        <div class="form-group @if($errors->has('quick_notes')) {{'has-error'}} @endif">
{{--                        @if(Auth::user()->level_id == 5 || Auth::user()->level_id == 3)--}}
                            <div class="form-group">
                                <label>Receipt <span class="asterik-red" > (*)</span></label>
                                <div class="box-header with-border btn-warning">
                                    <h3 class="box-title"><div
                                                id="uploadTitle"></div>
                                    </h3>
                                </div>
                                <div>
                                    {!! $application->bank_notes !!}
                                <div>

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
{{--                        @endif--}}
                        <br>

                        <label>Email Order Confirmation &nbsp;</label>

                        {!! Form::checkbox('email_confirmation', 1, true,array('class' => '')) !!}

                        <input type="button" id="submit1" value="Submit" class="btn btn-primary pull-right"
                               style="margin-left: 2px;" onclick="validate()">
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

        $(document).ready(function () {
            var senderId;
            var beneficiaryId;



        });
        senderId = $("#sender").find('option:selected').val();
        const senderOldValue = '{{ old('sender_id') }}';
        const beneficiaryOldValue = '{{ old('beneficiary_id') }}';

        if(senderOldValue != '') {
            senderId = senderOldValue;

            fetchSenderData();
            $("#senderInformation").css("display","block");
            fetchBeneficiaries();
            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
            findSpecialSender(senderId);
            @endif
        }
        if(senderOldValue != '' && beneficiaryOldValue != ''){
            beneficiaryId = beneficiaryOldValue;
            fetchBeneficiaries(beneficiaryOldValue);

            fetchBeneficiaryData();
        }

        function validate() {
            var r = document.getElementById('receipt');

            @if(Auth::user()->level_id == 5)
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
                @else
                if(r != null && r.value != ""){
                    if (receipt.files[0].size / 1024 / 1024 > 10) {
                        alert('File size exceeds 10 MB');
                        $("#receipt_div").addClass("has-error");
                    } else {
                        $("#receipt_div").removeClass("has-error");
                        document.getElementById("submit").submit();
                        document.getElementById("submit1").disabled = true;
                        document.getElementById("submit1").value = 'Submitting.Please wait...';
                    }
                }
                else {
                    document.getElementById("submit").submit();
                    document.getElementById("submit1").disabled = true;
                    document.getElementById("submit1").value = 'Submitting.Please wait...';
                }
                @endif
            }

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3)
            $('#sender').change(function () {
                $('#senderInformation').css('display', 'block');
                senderId = $(this).find('option:selected').val();
                fetchSenderData();
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                findSpecialSender(senderId);
                @endif
                /* $("#serviceCharg").val(senderId);*/
                $('#add_ben').removeAttr('disabled');
                fetchBeneficiaries();
                var url = "{{route('transaction.beneficiary.add',":id")}}".replace(':id', 'id=' + senderId);
                $('#add_ben').attr('data-url', url);
            });
            @else
                senderId = $('#sender').find('option:selected').val();
            $('#add_ben').removeAttr('disabled');
            fetchBeneficiaries();

            var url = "{{route('transaction.beneficiary.add',":id")}}".replace(':id', 'id=' + senderId);
            $('#add_ben').attr('data-url', url);

            @endif
            $('#payment_type').change(function () {
                fetchDistricts();
            });

            function findSpecialSender(senderId) {
                $.ajax({
                    url: 'fetch-service-charge',
                    method: 'GET',
                    data: {Id: senderId},
                    dataType: 'json',
                    success: function (data) {

                        if (data == 0) {
                        } else {
                            $(".serv").val(data);

                        }

                    }
                });
            }


            function fetchSenderData() {
                $("#senderInformation").html('<img class="mt-3 mb-3 pl-1" src="{{ asset('assets/img/loader.gif') }}"><br><br>');
                $.ajax({
                    url: 'fetch-sender-data',
                    method: 'GET',
                    data: {Id: senderId},
                    dataType: 'json',
                    success: function (data) {


                        $("#sender").html('<option selected value=' + senderId + '></option>+');

                        var url = "{{route('transaction.sender.edit',":id")}}".replace(':id', data.sender_id);
                        var senderUrl = "{{route('sender.ajax.changeStatus',":id")}}".replace(':id', data.sender_id);

                        if (data.diff_in_days < 0) {
                            data.diff_in_days = '<span class="btn btn-sm btn-outline-danger">Expired</span>' + ' (' + Math.abs(data.diff_in_days) + ' day(s) ago )';
                        } else {
                            data.diff_in_days = '( ' + data.diff_in_days + ' day(s) left )';
                        }
                        $('.alert').remove();
                        document.getElementById("submit1").disabled = false;
                        if(data.sender_status_id == 5){
                            document.getElementById("submit1").disabled = true;
                            $('.firstpage').before(notify('danger', 'Please verify identification detail and change sender status for transaction.'));
                        }
                        $("#senderInformation").html(
                            "<div class=\"box-header with-border  border-color1 \">\n" +
                            "\n" + "<span style='color:black;' class=\"info-box-number\">\n" + "<span  class=\"box-title\">\n" + "\n" + data.full_name + ' (' + data.formattedId + ')' +
                            "</span>\n" + "</span>\n" + "<div class=\"box-tools pull-right\" style=\"top: 10px;\">\n" +
                            "<span style='color:black;'>\n" + "</span>\n" + "@if(in_array(Auth::user()->level_id, [1,2,3]))<a type=\"button\" data-url=" + senderUrl + " data-toggle=\"modal\" data-target=\"#sendmoney-modal\" class=\"btn btn-sm btn-outline-danger active\">\n" + data.status +
                            "</a>@else <span class=\"btn btn-sm btn-outline-danger active\">" + data.status + "</span> @endif\n" +
                            "@if(in_array(Auth::user()->level_id, [1,2,3]))<a type=\"button\" data-url=" + url + " data-toggle=\"modal\" data-target=\"#sendmoney-modal\" class=\"btn btn-info btn-sm\"><i\n" + "class=\"fa fa-edit\"></i>\n" +
                            "</a>@endif\n" + /*"<button type=\"button\" class=\"btn btn-info  btn-xs\" data-widget=\"remove\"><i\n" +
"class=\"fa fa-times\"></i></button>\n" +*/ "</div>\n" + "</div>\n" + "<!-- /.box-header -->\n" +
                            "<div class=\"box-body\" style=\"padding: 0;\">\n" + "<div class=\"info-box bg-card-color1\" style=\"margin-bottom: 0\">\n" +
                            "\n" + "\n" + "<div class=\"info-box-content\" style=\"margin-left: 0\">\n" + "<div class=\"row\">\n" +
                            "<div class=\"col-md-9\">\n" + " " + data.number + "| " + data.email + "<br>" + data.street + " " + data.suburb + " " + data.state + " <br>" + data.postcode + " " + data.country + "" +
                            "</div>\n" + "<div class=\"col-md-3 text-right\">\n" + "</div>\n" + "</div>\n" +
                            "<div style=\"border-top: 1px solid #dcdcdc;margin: 4px 0\"></div>\n" + "<div class=\"row\">\n" + "<div class=\"col-md-9\">\n" +
                            "DOB: " + data.dob + " | ID TYPE: " + data.name + " | ID NO: " + data.id_number + "<br>" +
                            "ISSUED BY: " + data.issued_by + " | EXPIRY DATE: " + data.expiry_date + "\n" +
                            "</div>\n" + "<div class=\"col-md-3 text-right\">\n" + "(" + data.identificationStatus + ")<br>" + data.diff_in_days + "\n" + "</div>\n" + "</div>\n" +
                            "</div>\n" + "<!-- /.info-box-content -->\n" + "</div>\n" + "</div>\n" + "<!-- /.box-body -->\n" +
                            "<div class=\"box-footer border-color1  bg-card-color1 text-right\">\n" /*+ "<div class=\"pull-left\">\n" + "if sending for company:\n" + "\n" +
"<select class=\"\" style=\"width: auto!important;color: black;\" tabindex=\"-1\"\n" + "aria-hidden=\"true\">\n" +
"<option selected=\"selected\">Alabama</option>\n" + "<option>Alaska</option>\n" + "<option>California</option>\n" + "<option>Delaware</option>\n" +
"<option>Tennessee</option>\n" + "<option>Texas</option>\n" + "<option>Washington</option>\n" +
"</select>\n" + "<button type=\"button\" class=\"btn btn-success  btn-xs\"><i class=\"fa fa-user\">\n" +
"Add Company</i></button>\n" + "\n" + "</div>\n"*/ + "<div class=\"pull-right\">\n" + "Added By: " + data.added_by + "\n" +
                            "</div>\n" + "\n" + "</div>"
                        );
                    }
                });


            }

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
                            fetchBeneficiaryData()
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
                            "@if(in_array(Auth::user()->level_id, [1,2,3])) <a type=\"button\" data-url=" + url + " data-toggle=\"modal\" data-target=\"#sendmoney-modal\" class=\"btn btn-info btn-sm\"><i\n" + "class=\"fa fa-edit\"></i>\n" + "</a>@endif\n" + "\n" + "</div>\n" + "</div>\n" + "<div class=\"box-body\" style=\"padding: 0;\">\n" +
                            "<div class=\"info-box bg-card-color1\" style=\"margin-bottom: 0\">\n" + "\n" + "\n" + "<div class=\"info-box-content\" style=\"margin-left: 0\">\n" + "<span class=\"\">\n"
                            + "<div class=\"row\">\n" + "<div class=\"col-md-12\">\n" + data.number + "<br>\n" + data.street + " " + data.suburb + " " + data.state + " " + data.country + "</div>\n" + "<div style=\"border-top: 1px solid #f4f4f4;margin: 4px 0\"></div>\n"
                            + "</div>\n" + "<div class=\"row\">\n" + "<div class=\"col-md-12\">\n" + "<div style=\"border-top: 1px solid #dcdcdc;margin: 4px 0\"></div>\n" + data.accountName + " | ACCOUNT NO: " + data.accountNo + " <br> BRANCH: " + data.bsb + " | " + data.bankName + " \n" + "@if(in_array(Auth::user()->level_id, [1,2,3]))  <a  type=\"button\" data-toggle=\"modal\"\n" + " data-url=" + url1 + " title=\"Change Default Account\"\n" +
                            "data-target=\"#sendmoney-modal\"\n" + " class=\"btn btn-primary btn-sm pull-right\"><i\n" + " class=\"fa fa-edit\"></i> Change Default</a> @endif\n" + "</div>\n" + "</div>\n" + "</span>\n" +
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


            $("input[name='sending_amount']").on('keyup', function () {

                sendingAmt = parseFloat($("input[name='sending_amount']").val());
                exchangeAmt =  getExchangeRateBySendingAmount(sendingAmt);
                paymentAmt = sendingAmt * exchangeAmt;
                $("input[name='payment_amount']").val(paymentAmt.toFixed(2));
                totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());

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

              //      sendingAmt = paymentAmt / exchangeAmt;
                $("input[name='sending_amount']").val(sendingAmt.toFixed(2));
                totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());


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
            $("input#exchangeRate").on('input', function() {
                sendingAmt = parseFloat($("input[name='sending_amount']").val());
                exchangeAmt =  parseFloat($("input#exchangeRate").val());
                paymentAmt = sendingAmt * exchangeAmt;
                $("input[name='payment_amount']").val(paymentAmt.toFixed(2));
                {{--totalPayment = sendingAmt + parseFloat("{{$serviceCharge}}");--}}
                totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());

                $("#uploadTitle").html('You are requested to upload receipt with AUD. ' + totalPayment.toFixed(2) + ' on following detail: <br>' +
                    @if($application->pay_id)
                    'For quick settlements under $2K, please use PayID. <br>' +
                    '<img style="height:60px;width:auto;margin-left:-10px;" src="{{asset('assets/images/pay_id.svg')}}" > {{$application->pay_id}}' + '' +
                    ' <button type="button" class="btn btn-warning" onclick="copyToClipboard()">  Copy <i class="fa fa-copy"></i> </button> <br> ' +
                    'Alternative Payment option: <br> ' +
                    @endif
                    'Account Name: {{ $application->bank_account_name }} <br>' +
                    'BSB: {{ $application->bank_bsb }} <br> ' +
                    'A/C: {{ $application->bank_account_number }} <br> ' +
                    'Bank: {{ $application->bank_name }} <br> ');
                $("#uploadTitle").css("padding", "7px");
            });
            $("input#serviceChargField").on('input', function(){
                // trigger a change in sending amount
                $("input[name='sending_amount']").trigger('keyup');
            })
            // process the sender form
            $(document).on("submit", "#add-sender", function (event) {
                var phn_num = $("#number").val();
                var label = $('#number').next('label.error ');
                label.remove();
                if(phn_num.length !=0) {
                    if (!(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num))) {
                        $("#number").after('<label class="error">Please Enter Valid Number.</label>');
                        return false;
                    }
                }
                var formData = new FormData($(this)[0]);
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();
                $("#add-senderPopup").html('Submitting...');
                $("#add-senderPopup").attr('disabled', true);
                /* setTimeout(function(){
                     $("#add-senderPopup").attr('disabled', false);
                     $("#add-senderPopup").html('Save');
                 }, 2000);
     */

// process the form
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    encode: true
                })

                    .done(function (result) {

                        if (result.status == 1) {
                            var select = $('#sender');
                            select.append($('<option value="' + result.data.sender_id + '" data-status="' + result.data.status + '" data-phone-number="' + result.data.number + '" data-email="' + result.data.email + '">' + result.data.fullname + '</option>'));

                            select.val(result.data.sender_id).trigger('change'); //Necessary for select2

                            $('.modal').modal('hide');
                            toastr.success('Sender has been added successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //  $('.box-primary').before(notify('success', 'Sender Added Successfully!'));
                        } else {
                            $.each(result.data.errors, function (i, v) {
                                $('#add-sender').find('#' + i).after('<label class="error ">' + v + '</label>').closest('.form-group').addClass('has-error');
                                $("#add-senderPopup").attr('disabled', false);
                                $("#add-senderPopup").html('Save');

                            });
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
            });
            $(document).on("submit", "#update-account-form", function (event) {
                var formData = $(this).serialize();
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();

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
                            $('.modal').modal('hide');
                            fetchBeneficiaryData();
                            toastr.success('Account has been updated successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //    $('.box-primary').before(notify('success', 'Account Updated Successfully!'));
                        } else {
                            $.each(result.data.errors, function (i, v) {
                                $('#update-account-form').find('#' + i).after('<label class="error ">' + v + '</label>').closest('.form-group').addClass('has-error');
                            });
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
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
            $(document).on("submit", "#editsenderf", function (event) {
                var phn_num = $("#number").val();
                var label = $('#number').next('label.error ');
                label.remove();
                if(phn_num.length !=0) {
                    if (!(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num))) {
                        $("#number").after('<label class="error">Please Enter Valid Number.</label>');
                        return false;
                    }
                }
                            // var formData = $(this).serialize();
                var formData = new FormData($(this)[0]);
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();

// process the form
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    encode: true
                })
                    .done(function (result) {
                        if (result.status == 1) {
                            var select = $('#sender');
                            $('#senderInformation').css('display', 'block');
                            // senderId = $('.select2').find('option:selected').val();
                            select.append($('<option value="' + result.data.sender_id + '" data-status="' + result.data.status + '" data-phone-number="' + result.data.number + '" data-email="' + result.data.email + '" selected>' + result.data.fullname + '</option>'));

                            fetchSenderData();

                            $('.modal').modal('hide');
                            toastr.success('Sender has been updated successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //  $('.box-primary').before(notify('success', 'Sender Updated Successfully!'));
                        } else {
                            $('.modal').modal('hide');
                            toastr.error('Oops! Something went wrong!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //   $('.box-primary').before(notify('danger', 'Something went wrong!'));
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
            });
            $(document).on("submit", "#editbeneficiary", function (event) {
                var formData = $(this).serialize();
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();
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
                            beneficiary.append($('<option value="' + result.data.beneficiary_id + '" data-phone-number="' + result.data.number + '" data-phone-address="' + result.data.address + '" selected>' + result.data.fullname + '</option>'));

                            $('#beneficiaryInformation').css('display', 'block');
                            beneficiaryId = $('.selectBeneficiary').find('option:selected').val();
                            fetchBeneficiaryData();

                            $('.modal').modal('hide');
                            toastr.success('Beneficiary has been changed Successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            //   $('.box-primary').before(notify('success', 'Beneficiary Updated Successfully!'));
                        } else {
                            $('.modal').modal('hide');
                            toastr.error('Something went wrong!', {
                                closeButton: true,
                                progressBar: true,
                            });
                            // $('.box-primary').before(notify('danger', 'Something went wrong!'));
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
            });
            // change sender status
            $(document).on("submit", "#change-status-sender", function (event) {
                var formData = new FormData($(this)[0]);
                var url = $(this).attr('action');
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('label.error').remove();
                $(this).find('.callout').remove();
                $("#change-status-popup").html('Applying...');
                $("#change-status-popup").attr('disabled', true);

// process the form
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    encode: true
                })

                    .done(function (result) {

                        if (result.status == 1) {

                            $('.modal').modal('hide');
                            senderId = result.data.sender_id;
                            fetchSenderData();
                            //$('.box-primary').before(notify('success', 'Sender Status Changed Successfully!'));
                            toastr.success('Sender Status has been changed Successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        } else {
                            $.each(result.data.errors, function (i, v) {
                                $('#change-status-sender').find('#' + i).after('<label class="error ">' + v + '</label>').closest('.form-group').addClass('has-error');
                                $("#change-status-popup").attr('disabled', false);
                                $("#change-status-popup").html('Apply');

                            });
                        }
                        setTimeout(function () {
                            $('.callout').remove()
                        }, 2500);
                    });
                event.preventDefault();
            });


            function notify(type, text) {
                return '<div class="alert alert-' + type + '"><p>' + text + '</p></div>';
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
                $('.sender-select2').select2({
                    placeholder: 'Choose a sender',
                    minimumInputLength: 3,
                    theme: "bootstrap",
                    ajax: {
                        url: 'senders/getSendersDropDownData',
                        dataType: 'json',
                        delay: 1000,
                        processResults: function (result) {
                            return {
                                results: result
                            }
                        }
                        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                    }
                });


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
                var diff = test_amount - key_amount;

		@if(Auth::user()->level_id==1 || Auth::user()->level_id==2 || Auth::user()->level_id==3)
			new_exchange_rate = val;
	    @else
                if(diff >= 0) {
                    new_exchange_rate = val;
                    return false;
	        	}
		@endif

            });

            $("input[name='exchange_rate']").val(new_exchange_rate);
            $("input[name='exchange_rate']").trigger('change');
            return new_exchange_rate;
        }

        $(document).ready(function(){
            getExchangeRateBySendingAmount(0);
        })
        // max limit logic
        @if(Auth::user()->level_id == 3 && $agentIsRestricted)

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
            })
        });
        @endif

    </script>
@endsection
