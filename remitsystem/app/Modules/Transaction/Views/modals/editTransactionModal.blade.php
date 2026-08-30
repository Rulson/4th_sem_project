@php
    $serviceCharge1 = App\Modules\Settings\Models\Settings::first();
    $serviceCharge = $serviceCharge1->service_charge;
    $totalamount = $transaction->sending_amount + $serviceCharge;
@endphp
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Edit Transaction</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<form method="post" action="{{route('transaction.transaction.edit.save',$transaction->id)}}" enctype="multipart/form-data" id="save-edit-transaction-form">
{{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group @if($errors->has('sender_id')) {{'has-error'}} @endif"
                     id="sender_div">
                    <label>Select Sender<span class="asterik-red" > (*)</span></label>

                    <div class="input-group my-colorpicker2 colorpicker-element">
                        {!!Form::select('sender_id',$sender_block,null, array('class' => 'form-control sender-select2 select2','id'=>'sender','required'=>'required'))!!}
                    </div>
                    <span style="color: red;" id="sender_select_msg"></span>

                    <!-- /.input group -->
                </div>
            @if($errors->has('sender_id'))
                {!! $errors->first('sender_id', '<label class="control-label text-danger"
                                                       for="inputError">:message</label>') !!}
            @endif
            </div>

            <div class="col-md-12">
                <div class="form-group @if($errors->has('beneficiary_id')) {{'has-error'}} @endif"
                     id="beneficiary_div">
                    <label>Select Beneficiary<span class="asterik-red" > (*)</span></label>

                    <div class="input-group my-colorpicker2 colorpicker-element">
                        <select class="form-control selectBeneficiary select2 " id="beneficiary-id"
                                name="beneficiary_id" required>
                            <option></option>



                        </select>


                    </div>
                    <span style="color: red;" id="beneficiary_select_msg"></span>
                    <!-- /.input group -->
                </div>
                @if($errors->has('beneficiary_id'))
                    {!! $errors->first('beneficiary_id', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif

            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <fieldset class="form-group @if($errors->has('sending_amount')) {{'has-error'}} @endif "
                                  id="sending_amount_div">
                            <label>Sending Amount<span class="asterik-red" > (*)</span></label>
                            <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">AUD</span>
                        </span>
                                <input type="number" min="1" step="0.001" class="form-control" value="{{$transaction->sending_amount}}" name="sending_amount"
                                       id="sending_amount"
                                       placeholder="Enter Sending Amount" required>
                            </div>
                            @if($errors->has('sending_amount'))
                                {!! $errors->first('sending_amount', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </fieldset>

                        <div class="form-group @if($errors->has('exchange_rate')) {{'has-error'}} @endif" id="payment_amount_div">
                            <label>Exchange Rate<span class="asterik-red" > (*)</span></label>
                            <input type="text" class="form-control" value="{{$transaction->exchange_rate}}"
                                   name="exchange_rate" id="exchangeRate"
                                   placeholder="Enter Exchange Rate" required>

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
                                <input type="number" class="form-control" step="0.01" name="payment_amount" value="{{$transaction->payment_amount}}" id="paymentAmount"
                                       placeholder="Enter Payment Amount" required>
                            </div>
                            @if($errors->has('payment_amount'))
                                {!! $errors->first('payment_amount', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </fieldset>
                        <div class="form-group">
                            <label>Payment Type In Nepal <span class="asterik-red" > (*)</span></label>
                            <select class="form-control" name="payment_type" id="payment_type" required>
                                <option {{($transaction->payment_type == 'Bank Transfer')?"selected":''}}>Bank Transfer</option>
                                <option {{($transaction->payment_type == 'Local Remit')?"selected":''}}>Local Remit</option>
                               </select>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>Pickup District <span class="asterik-red" > (*)</span></label>
                            <select class="form-control" name="pickup_district" id="district_name"></select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Order Details</h3>


                    <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="info-box no-box-shadow b-0 ">
                            <div class="info-box-content" style="margin-left: 0">
                                <ul class="list-group" style="margin: 0; border: 0">
                                    <li class="list-group-item b-0">
                                        Sending Amount to Nepal: <span class="pull-right"
                                                                       id="sendingamount">AUD {{$transaction->sending_amount}}</span>
                                    </li>
                                    @if(Auth::user()->level_id== 1 || Auth::user()->level_id == 2)
                                        <li class="list-group-item  b-0">
                                           Service Charge: <span style="margin-left: 540px;">AUD</span> <input style="width: 100px;" type="number" step="0.01"  value="{{$transaction->service_charge}}" min="0" name="service_charge"  class="pull-right" id="serviceCharg" required>
                                        </li>
                                    @else
                                        <li class="list-group-item  b-0">
                                            Service Charge: <span class="pull-right"  id="servicecharge">AUD {{$transaction->service_charge}}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item b-0">
                                        <h4><b>Total Payment: </b> <span class="pull-right"
                                                                         id="totalpayment"><b>AUD {{$transaction->sending_amount + $transaction->service_charge}}</b></span>
                                        </h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span  id="uploadTitle"></span></h3>
                    </div>

                    <div class="input-group control-group increment @if($errors->has('receipt')) {{'has-error'}} @endif" id="receipt_div">
                        <input type="file" name="receipt[]" class="form-control"
                               accept="image/jpeg , image/jpg, image/gif, image/png ,application/pdf" id="receipt">
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
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary save-edit-transaction-button">Save</button>
    </div>
</form>


<script>

    sendingAmt = parseFloat($("input[name='sending_amount']").val());
    totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());
    $("#uploadTitle").text('PLEASE UPLOAD RECEIPT OF AUD ' + totalPayment + ' BELOW');
    $("input[name='sending_amount'],input[name='exchange_rate']").on('keyup', function () {
        sendingAmt = parseFloat($("input[name='sending_amount']").val());
        exchangeAmt = parseFloat($("input[name='exchange_rate']").val());

        paymentAmt = sendingAmt * exchangeAmt;
        $("input[name='payment_amount']").val(paymentAmt.toFixed(2));
        $("#sendingamount").text('AUD' + ' ' + sendingAmt);
        totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());
        $("#totalpayment").text('AUD' + ' ' + totalPayment);
        $("#uploadTitle").text('PLEASE UPLOAD RECEIPT OF AUD ' + totalPayment + ' BELOW');


    });
    $("input[name='service_charge']").on('keyup', function () {
        sendingAmt = parseFloat($("input[name='sending_amount']").val());
        totalPayment = sendingAmt + parseFloat($("input[name='service_charge']").val());
        $("#totalpayment").text('AUD' + ' ' + totalPayment);
        $("#uploadTitle").text('PLEASE UPLOAD RECEIPT OF AUD ' + totalPayment + ' BELOW');

    });
    $("input[name='payment_amount']").on('keyup', function () {

        paymentAmt = parseFloat($("input[name='payment_amount']").val());
       exchangeAmt = parseFloat($("input[name='exchange_rate']").val());
        a = paymentAmt / exchangeAmt;
        var sendingAmt = a.toFixed(2);
        $("#sendingamount").text('AUD' + ' ' + sendingAmt);
        totalPayment = parseFloat(sendingAmt) + parseFloat($("input[name='service_charge']").val());
        $("#totalpayment").text('AUD' + ' ' + totalPayment);
        $("#uploadTitle").text('PLEASE UPLOAD RECEIPT OF AUD ' + totalPayment + ' BELOW');
        $("input[name='sending_amount']").val(sendingAmt);
    });
    $('#payment_type').change(function () {
        fetchDistricts();
    });

    $(".btn-success-receipt").click(function () {
        var html = $(".clone").html();
        $(".increment").after(html);
    });

    $("body").on("click", ".btn-receipt-danger", function () {
        $(this).parents(".control-group").remove();
    });
    function fetchDistricts() {
        districtName = "{{$transaction->pickup_district}}";
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
                if(districtName == arrayItem){
                    districts.push('<option value="' + arrayItem + '" selected>' + arrayItem + '</option>');
                }else {
                    districts.push('<option value="' + arrayItem + '">' + arrayItem + '</option>');
                }
            });
            $('#district_name').html(districts);
            $('#district_name').closest('.form-group').show();

        } else {
            $('#district_name').closest('.form-group').hide();
            $('#district_name').val('');
        }

    }
    var payment_type = "{{$transaction->payment_type}}";

    if (payment_type == 'Local Remit'){
        fetchDistricts();
    }
    var senderId = $('#sender').find('option:selected').val();
    var beneficiaryId = "{{$transaction->beneficiary_id}}";
    fetchBeneficiaries(beneficiaryId);
    function fetchBeneficiaries(beneficiary_id='') {
        $.ajax({
            url: '{{route('fetch.beneficiaries')}}',
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
                    fetchBeneficiaryData();
                }else{

                    $('#beneficiaryInformation').css('display', 'none');
                }
            }
        });
    }
    $('.sender-select2').select2({
        placeholder: 'Choose a sender',
        minimumInputLength: 3,
        theme: "bootstrap",
        ajax: {
            url: "{{route('sender.data.ajax')}}",
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
    $('#sender').change(function () {

        senderId = $('#sender').find('option:selected').val();
        fetchBeneficiaries();
    });






</script>
