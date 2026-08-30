@extends('layouts.main')
@section('title', 'Edit Agent')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('agents.index')}}" title="All Agents">Agents</a></li>
    <li class="breadcrumb-item active">Edit</li>

@stop

@section('content')
    @include('flash::message')

    {!!Form::model($data, array('route' => array('agent.update', $data->id), 'class' => '', 'method' => 'POST','files'=>'true'))!!}
        {{csrf_field()}}
        <div class="animated fadeIn">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header">
                        <strong>Edit Agent</strong>
                        <small>Form</small>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">

                                <strong>General Information</strong>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name<span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="first_name" value="{{$data->first_name}}"
                                                   class="form-control"
                                                   id="firstName"
                                                   placeholder="Enter First Name">
                                        </div>
                                        {!! $errors->first('first_name', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name<span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="last_name" value="{{$data->last_name}}"
                                                   class="form-control"
                                                   id="lastName"
                                                   placeholder="Enter Last Name">
                                        </div>
                                        {!! $errors->first('last_name', '<p class="text-danger">:message</p>') !!}

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number<span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="phone_number" value="{{$data->phone_number}}"
                                                   class="form-control"
                                                   id="phoneNumber"
                                                   placeholder="Enter Phone Number">
                                        </div>
                                        {!! $errors->first('phone_number', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date Of Birth<span class="asterik-red" > (*)</span></label>
                                            <input type="text" placeholder="mm/dd/yyyy"
                                                   class="form-control" name="dob" value="{{$data->dob}}" id="dob">
                                        </div>
                                        {!! $errors->first('dob', '<p class="text-danger">:message</p>') !!}

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email<span class="asterik-red" > (*)</span></label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{$data->email}}" id="email"
                                                   placeholder="Enter Email">
                                        </div>
                                        {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Service Charge<span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="service_charge" value="{{ $data->service_charge }}"
                                                   class="form-control"
                                                   id="service_charge"
                                                   placeholder="Enter Service Charge">
                                        </div>
                                        {!! $errors->first('service_charge', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <strong>Address Information</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="custom-search-input">
                                            <label>Google Address</label>
                                            <input type="text"  name="google_address" class="form-control" id="google-address"
                                                   placeholder="Type Google address here">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                                            <label class="control-label">Street<span class="asterik-red" > (*)</span></label>
                                            {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street','placeholder'=>'Enter Street'))!!}
                                            @if($errors->has('street'))
                                                {!! $errors->first('street', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif
                                            <input type="hidden" name="lat">
                                            <input type="hidden" name="long">
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                                            <label class="control-label">Suburb<span class="asterik-red" > (*)</span></label>
                                            {!!Form::text('suburb', null, array('class' => 'form-control', 'id'=>'suburb', 'placeholder'=>'Enter Suburb'))!!}
                                            @if($errors->has('suburb'))
                                                {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif">
                                            <label class="control-label">State<span class="asterik-red" > (*)</span></label>
                                            {!!Form::text('state', null, array('class' => 'form-control', 'id'=>'state', 'placeholder'=>'Enter State'))!!}
                                            @if($errors->has('state'))
                                                {!! $errors->first('state', '<label class="control-label text-danger"
                                                                                       for="inputError">:message</label>') !!}
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
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
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <label class="control-label">Country<span class="asterik-red" > (*)</span></label>
                                            {!!Form::select('country_list_id', $countries,13, array('class' => 'form-control', 'id'=>'country'))!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o"
                                                                         aria-hidden="true"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
@section('page-script')
    @parent
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
            async defer></script>

    <script>
        $('.select2-state').select2({
            // placeholder:'Choose Status'
            theme: "bootstrap"
        });
        var date = new Date();
        $(function () {


            $('input[name="dob"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                maxDate: date
            });
            $('input[name="dob"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
            });

            $('input[name="dob"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        })
        $(document).ready(function(){
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
                    componentRestrictions: {country: "au"}
                };

                var autocomplete = new google.maps.places.Autocomplete(input, options);
                //    autocomplete.setComponentRestrictions({'country': ['au']});
                autocomplete.addListener('place_changed', function () {
                    var loca = $(input).val();

                    var place = autocomplete.getPlace();
                    // place variable will have all the information you are looking for.
                    $('#lat').val(place.geometry['location'].lat());
                    $('#long').val(place.geometry['location'].lng());

                    for (var [key, value] of Object.entries(componentForm)) {
                        //console.log(key, value);
                        $('#' + value).val('');
                    }
                    /*   for (var component in componentForm) {
                           $('#'+component).val('');
                       }*/
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];

                        if (componentForm[addressType]) {
                            var val = place.address_components[i].long_name;
                            regex = new RegExp("("+val+")(?!.*\1)", "gi");
                            if (addressType === 'country') {
                                loca = loca.replace(regex, '');
                                $('#country').val($('#country option:contains(' + val + ')').first().val());
                            } else {
                                addr = $('#' + componentForm[addressType]).val() ? $('#' + componentForm[addressType]).val() + ' ' : '';
                                $('#' + componentForm[addressType]).val(addr + val);
                                loca = loca.replace(regex, '');
                            }
                            if(addressType === 'administrative_area_level_1') {
                                var val1 = place.address_components[4].short_name;
                                regex = new RegExp("("+val1+" )(?!.*\1)", "gi");
                                loca = loca.replace(regex, '');
                            }
                        }
                    }
                    loca = loca.replace(/(\s{1,}),+/g, '').replace(/\s\s+/g, ' ');

                    $('#street').val(loca.trim() + ' ' + $('#street').val());
                    /*var address = place.formatted_address;
                    var value = address.split(",");
                    //  console.log(value);
                    count = value.length;
                    country = value[count - 1];

                    state = value[count - 2];
                    street = value[count - 3];
                    document.getElementById("street").value = street;
                    //    console.log(country,state,street);
                    var z = state.split(" ");
                    //   document.getElementById("selCountry").text = country;
                    var i = z.length;
                    document.getElementById("suburb").value = z[1];
                    if (i > 2) {
                        document.getElementById("state").value = z[2];
                    }
                    if (i > 3) {
                        document.getElementById("postcode").value = z[3];
                    }*/
                });
            }

            $("#google-address").on("keyup",function(){

                google.maps.event.addDomListener(window, 'load', initialize());
                initialize();
            });
        })
    </script>

@stop
