@extends('layouts.min')
@section('title','Register')
@section('style')
    <link href="{{asset('assets/coreUI/vendors/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <?php
    $color = '';
    if(str_contains(request()->getHttpHost() ,'nepalpaisa')){
        $color = 'nepal-paisa';
    } elseif (str_contains(request()->getHttpHost() , 'dollarrupiya')){
        $color = 'dollar-rupiya';
    } else {
        $color = 'cash-nepal';
    }
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mx-4">
                    <div class="card-header">Register</div>

                    <div class="card-body p-4">
                        @include('flash::message')
                        <form action="{{route('register.storeUserClient')}}" method="post" id="newClientSubmit"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="agent_id" readonly value="{{request('agent_id')}}">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                  <div class="card">
                                      <div class="card-header">
                                          General Information
                                      </div>
                                      <div class="card-body">
                                          <div class="row">
                                              <div class="col-md-6">
                                                  <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                                                      <label>First Name (*)</label>
                                                      <input type="text" name="first_name" value="{{old('first_name')}}"
                                                             class="form-control" id="firstName"
                                                             placeholder="Enter First Name / Middle Name">
                                                  </div>
                                                  @if($errors->has('first_name'))
                                                      {!! $errors->first('first_name', '<label class="control-label text-danger"
                                                                                             for="inputError">:message</label>') !!}
                                                  @endif

                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                                                      <label>Last Name (*)</label>
                                                      <input type="text" name="last_name" value="{{old('last_name')}}"
                                                             class="form-control" id="lastName"
                                                             placeholder="Enter Last Name">
                                                  </div>
                                                  @if($errors->has('last_name'))
                                                      {!! $errors->first('last_name', '<label class="control-label text-danger"
                                                                                             for="inputError">:message</label>') !!}
                                                  @endif
                                              </div>

                                          </div>


                                          <div class="row">
                                              <div class="col-md-6">
                                                  <div class="form-group @if($errors->has('phone_number')) {{'has-error'}} @endif">
                                                      <label>Phone Number (*)</label>
                                                      <input type="text" name="phone_number" class="form-control"
                                                             id="phoneNumber" value="{{old('phone_number')}}"
                                                             placeholder="Enter Phone Number">
                                                  </div>
                                                  @if($errors->has('phone_number'))
                                                      {!! $errors->first('phone_number', '<label class="control-label text-danger"
                                                                                             for="inputError">:message</label>') !!}
                                                  @endif
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group @if($errors->has('dob')) {{'has-error'}} @endif">
                                                      <label>Date Of Birth (*)</label>
                                                      <input type="text" placeholder="mm/dd/yyyy" class="form-control"
                                                             value="{{old('dob')}}"
                                                             name="dob" id="dob1" autocomplete="off">
                                                  </div>
                                                  @if($errors->has('dob'))
                                                      {!! $errors->first('dob', '<label class="control-label text-danger"
                                                                                             for="inputError">:message</label>') !!}
                                                  @endif

                                              </div>
                                          </div>

                                      </div>
                                  </div>

                                        <!-- /.box-body -->
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Address Information</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                                                        <label class="control-label">Street<span class="asterik-red" > (*)</span></label>
                                                        {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street','placeholder'=>'Enter Unit no./ Street no./ Street Name'))!!}
                                                        @if($errors->has('street'))
                                                            {!! $errors->first('street', '<label class="control-label text-danger"
                                                                                                   for="inputError">:message</label>') !!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                                                        <label class="control-label">Suburb<span class="asterik-red" > (*)</span></label>
                                                        {!!Form::select('suburb',[], old('suburb'), array('class' => 'form-control','placeholder'=>'Choose Suburb', 'id'=>'suburb'))!!}
                                                        @if($errors->has('suburb'))
                                                            {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                                                   for="inputError">:message</label>') !!}
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif">
                                                        <label class="control-label">State<span class="asterik-red" > (*)</span></label>
                                                       {!!Form::select('state', australiaStateLists(), null, array('class' => 'form-control','placeholder'=>'choose state', 'id'=>'state'))!!}
                                                        @if($errors->has('state'))
                                                            {!! $errors->first('state', '<label class="control-label text-danger"
                                                                                                   for="inputError">:message</label>') !!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif">
                                                        <label class="control-label">Post Code<span class="asterik-red" > (*)</span></label>
                                                        {!!Form::text('postcode', null, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code'))!!}
                                                        @if($errors->has('postcode'))
                                                            {!! $errors->first('postcode', '<label class="control-label text-danger"
                                                                                                   for="inputError">:message</label>') !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Country<span class="asterik-red" > (*)</span></label>
                                                        {!!Form::select('country_list_id', $countries,13, array('class' => 'form-control', 'id'=>'country'))!!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('address_proof')) {{'has-error'}} @endif">
                                                        <label>Address Proof (*) [max size : 10MB]</label>
                                                        <div>
                                                            Phone bill, electricity bill, gas bill, bank statement etc
                                                        </div>

                                                        <input type="file" name="address_proof" id="address_proof">
                                                    </div>
                                                    @if($errors->has('address_proof'))
                                                        {!! $errors->first('address_proof', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif
                                                    <div class="front-back-toggle" style="display: none">Please upload the front part</div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Identification Details
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif">
                                                        {!!Form::label('id_type', 'Id Type (*)', array('class' => 'control-label')) !!}
                                                        {!!Form::select('id_type', identificationTypes(),null, array('class' => 'form-control', 'id'=>'id_type','placeholder'=>'Please select'))!!}
                                                    </div>
                                                        @if($errors->has('id_type'))
                                                            {!! $errors->first('id_type', '<label class="control-label text-danger"
                                                                                                   for="inputError">:message</label>') !!}
                                                        @endif

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('id_number')) {{'has-error'}} @endif">
                                                        <label>Id Number (*)</label>
                                                        <input type="text" name="id_number" class="form-control"
                                                               id="idNumber" value="{{old('id_number')}}"
                                                               placeholder="Enter Id Number">
                                                    </div>
                                                    @if($errors->has('id_number'))
                                                        {!! $errors->first('id_number', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif">
                                                        <label>Issued By (*)</label>
                                                        <div class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif" id="issued_by_diiv">
                                                            @if(isset($sender))     <select name="issued_by" class="form-control"><option value="{{$sender->issued_by}}">{{$sender->issued_by}}</option></select>
                                                            @else
                                                                <select name="issued_by" class="form-control"><option ></option></select>

                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($errors->has('issued_by'))
                                                        {!! $errors->first('issued_by', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif

                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('expiry_date')) {{'has-error'}} @endif">
                                                        <label>Expiry Date (*)</label>
                                                        <input type="text" class="form-control" autocomplete="off"
                                                               name="expiry_date" value="{{old('expiry_date')}}"
                                                               id="expiryDateClient"
                                                               placeholder="mm/dd/yyyy">
                                                    </div>
                                                    @if($errors->has('expiry_date'))
                                                        {!! $errors->first('expiry_date', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('image')) {{'has-error'}} @endif">
                                                        <label>Upload Id (*) [max size : 10MB]</label>
                                                        <input type="file" name="image" id="file">
                                                    </div>
                                                    @if($errors->has('image'))
                                                        {!! $errors->first('image', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif
                                                    <div class="front-back-toggle" style="display: none">Please upload the front part</div>

                                                </div>

                                                <div class="col-md-6" id="second-image-toggle">
                                                    <div class="form-group @if($errors->has('image1')) {{'has-error'}} @endif">
                                                        <label>Upload Id [max size : 10MB]</label>
                                                        <input type="file" name="image1" id="file">
                                                    </div>
                                                    @if($errors->has('image1'))
                                                        {!! $errors->first('image1', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif
                                                    <div class="front-back-toggle" style="display:none;">Please upload the back part</div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Referral
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif">
                                                        {!!Form::label('referral_code', 'Do you have a referral code ??', array('class' => 'control-label')) !!}
                                                        {!!Form::text('referral_code', null, array('class' => 'form-control', 'id'=>'referral_code','placeholder'=>'Enter referral code'))!!}
                                                    </div>
                                                    @if($errors->has('referral_code'))
                                                        {!! $errors->first('referral_code', '<label class="control-label text-danger"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- /.col -->
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="button" id="registerClient" value="Register"
                                           class="btn {{$color}}-front-btn btn-block btn-flat">
                                </div>
                            </div>
                            <!-- /.col -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
        async defer></script>


<script>

    $(document).ready(function () {
        var typingTimer;
        var doneTypingInterval = 1000;
        var $input = $('#phoneNumber');

        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        function doneTyping () {
            var phn_num = $("#phoneNumber").val();
            var label = $('#phoneNumber').closest('div.col-md-6').find('label.text-danger');
            label.remove();
            if(phn_num.length !=0){
                if(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num)){
                    return true;
                }
                else{
                    $("#phoneNumber").parent().after('<label class="error text-danger">Please Enter Valid Number.</label>');
                    return false;

                }
            }
        }

        function initialize() {
            var input = document.getElementById('google-address');
            var componentForm = {
                street_number: 'street',
                route: 'street',
                sublocality_level_2: 'street',
                sublocality_level_1: 'street',
                locality: 'suburb',
                administrative_area_level_1: 'state',
                country: 'country_list_id',
                postal_code: 'postcode'
            };
            var options = {
                types: ['geocode'],
                componentRestrictions: {country: "au"}
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            let addListener = autocomplete.addListener('place_changed', function () {
                var loca = $(input).val();

                var place = autocomplete.getPlace();

                $('#lat').val(place.geometry['location'].lat());
                $('#long').val(place.geometry['location'].lng());

                for (var [key, value] of Object.entries(componentForm)) {
                    $('#' + value).val('');
                }
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i].long_name;
                        if (addressType === 'country') {
                            $('#country').val($('#country option:contains(' + val + ')').first().val());
                            loca = loca.replace(val, '');
                        } else {
                            addr = $('#' + componentForm[addressType]).val() ? $('#' + componentForm[addressType]).val() + ' ' : '';
                            $('#' + componentForm[addressType]).val(addr + val);
                            loca = loca.replace(val, '');
                        }
                    }
                }
                var val1 = place.address_components[4].short_name;
                loca = loca.replace(val1,'');
                loca = loca.replace(/[ ,]/g, '');

                $('#street').val(loca.trim() + $('#street').val());
            });
        }

        $("#google-address").on("keyup", function () {

            google.maps.event.addDomListener(window, 'load', initialize());
            initialize();


        });
        var date = new Date();
        var dob = new Date();
        dob.setTime(dob.getTime()-(16*365*24*60*60*1000));
        $("#dob1").mask("99/99/9999");
        $("#dob").mask("99/99/9999");
        $("#expiry_date").mask('99/99/9999');
        $("#expiryDateClient").mask('99/99/9999');


        $('input[name="dob"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            maxDate: dob
        });
        $('input[name="expiry_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            minDate: date
        });
        $('input[name="dob"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });

        $('input[name="dob"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $('input[name="expiry_date"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });

        $('input[name="expiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        var countries = <?php echo getCountryListObject() ?>;
        var states = <?php echo AusStates() ?>;

        function handleIDChange(){
            var type = $('#id_type').val();
            var options = '';
            var options1 = '';
            $('#second-image-toggle').show();
            if(type == 1){
                $('#second-image-toggle').hide();
                $('.front-back-toggle').hide();
            }else if (type == 2 || type == 3){
                $('.front-back-toggle').show();
            }
            $.each(countries, function (key, val) {
                if (val.id == 154) {
                    selec = 'selected';
                } else {
                    selec = '';
                }
                options += '<option value="' + val.name + '" ' + selec + '> ' + (val.name === "Nepal" ? "Nepal,MOFA" : val.name ) + '</option> <br>';


            });
            $.each(states, function (key, val) {
                options1 += '<option value="' + val.name + '" > ' + val.name + '</option> <br>';
            });

            if (type == 1) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">' + options + '</select>');


            } else if (type == 2) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">' + options1 + '</select>');

            } else if (type == 3) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">' + options1 + '</select>');
            }

        }

        $("#id_type").change(function () {
            handleIDChange();
        })
        handleIDChange();

        $(document).on('input propertychange change', '#email', function () {
            var value = $(this).val();


            var url = "{{route('sender.email.check')}}";
            $.ajax({
                type: 'get',
                url: url,
                data: {email: value},
                dataType: 'json',
                encode: true
            }).done(function (result) {
                if (result == '1') {
                    $("#email-exist-msg").html('Email already exists');
                }else{
                    $("#email-exist-msg").html('');
                }

            });
        });
        var oldSuburb = "{{ old('suburb') }}";

        $('#suburb').select2({
            theme : "bootstrap",
            tags: true,
            createTag: function(newTag) {
                return {
                    id: newTag.term,
                    text: newTag.term
                };
            },
            ajax: {
                url: "{{ url('api/getSuburb') }}",
                type: "get",
                dataType: "json",
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.response
                    };
                },
            }
        })
        // Check if the old suburb exists and select it
        if (oldSuburb) {
            // Append the old suburb as an option and select it
            var option = new Option(oldSuburb, oldSuburb, true, true);
            $('#suburb').append(option).trigger('change');
        }

        $('#suburb').on('change', function (e) {
            var id = $(this).val();
            if(id){
                getAusStatePostcode(id);
            }

        });
        function getAusStatePostcode(id){
            var url = "{{ url('api/ausStatePostcode') }}"+'/'+id;
            $.ajax({
                type: 'GET',
                url: url,
                success:function(response)
                {
                    data = response.response;
                    if(data){
                        state = data['selected_state']['name'];
                        $('select[name="state"]').empty();
                        $.each(data['au_state'], function(key,value){
                            if( state == value){
                                $('select[name="state"]').append('<option value="'+ key +'" selected>'+ value +'</option>');
                            }
                            else{
                                $('select[name="state"]').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                        $('#postcode').empty();
                        $('#postcode').val(data['postcode'])
                    }
                }
            })
        }
    });

</script>
@endsection
