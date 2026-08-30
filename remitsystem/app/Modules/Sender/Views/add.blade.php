@extends('layouts.main')
@section('title', 'Add Sender')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{url('senders')}}" title="All Senders">Senders</a></li>
    <li class="breadcrumb-item active">Add</li>

@stop
@section('content')
    <style>
        .pac-container {
            z-index: 10000 !important;
        }
    </style>
    @include('flash::message')
    {!!Form::open(array('route' => ['sender.store'],'files'=>'true'))!!}
    <div class="animated fadeIn">
        @include('Sender::form')
        <button type="submit" style="margin-right: 15px;margin-bottom: 10px;margin-top: -18px;" class="btn btn-primary pull-right" value="Save">Save</button>
        <!--<input type="submit" style="margin-right: 15px;margin-bottom: 10px;margin-top: -18px;" onclick="this.disabled=true;this.value='Submitting, please wait...';this.form.submit();"
               class="btn btn-primary pull-right " value="Save"/>-->
    </div>
    {!!Form::close()!!}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
            async defer></script>


    <script>


        $(document).ready(function(){
            var typingTimer;                //timer identifier
            var doneTypingInterval = 1000;  //time in ms, 5 second for example
            var $input = $('#number');

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
                var phn_num = $("#number").val();
                var label = $('#number').next('label.text-danger ');
                label.remove();
                if(phn_num.length !=0){
                    if(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num)){

                        return true;
                    }
                    else{
                        $("#number").after('<label class="error text-danger">Please Enter Valid Number.</label>');
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
            var countries = <?php echo getCountryListObject() ?>;
            var states = <?php echo AusStates() ?>;

            function handleIDChange(){
                var type = $('#id_type').val();

                var options = '';
                var options1 = '';
                $.each(countries, function (key, val) {
                    // alert(key + val);
                    if(val.id == 154){
                        selec = 'selected';
                    }else{
                        selec='';
                    }
                    options += '<option value="'+val.name+'" '+selec+'> '+(val.name === "Nepal" ? "Nepal,MOFA" : val.name ) +'</option> <br>';


                });  $.each(states, function (key, val) {

                    // alert(key + val);
                    options1 += '<option value="'+val.name+'" > '+val.name +'</option> <br>';


                });

                if (type == 1) {
                    $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options+'</select>');


                } else if (type == 2) {
                    $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');

                } else if (type == 3) {
                    $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');
                }
            }
            $("#id_type").change(function () {
                handleIDChange();
            })
            handleIDChange()

        })
    </script>

@stop
