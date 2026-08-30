@extends('layouts.main')
@section('title', 'Edit Sender')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{url('senders')}}" title="All Senders">Senders</a></li>
    <li class="breadcrumb-item active">Update</li>

@stop
@section('content')
            @include('flash::message')
            {!!Form::model($sender, array('route' => array('sender.update', $sender->sender_id), 'class' => '', 'method' => 'POST','files'=>'true'))!!}
            <div class="animated fadeIn">
                @include('Sender::form')
                <!--<input type="submit" class="btn btn-primary" value="Save" />-->
                <button type="submit" class="btn btn-primary" value="Save">Save</button>
            </div>
            {!!Form::close()!!}
@stop
@section('page-script')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
            async defer></script>
    <script>
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
                street_number:'street',
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
            var autocomplete = new google.maps.places.Autocomplete(input,options);
            autocomplete.addListener('place_changed', function () {
                var loca = $(input).val();
                var place = autocomplete.getPlace();
                // place variable will have all the information you are looking for.
                $('#lat').val(place.geometry['location'].lat());
                $('#long').val(place.geometry['location'].lng());


                /*var address = place.formatted_address;
                var value = address.split(",");
                //  console.log(value);
                count = value.length;
                country = value[count - 1];

                state = value[count-2];
                street = value[count-3];
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
                for (var component in componentForm) {
                    $('#'+component).val('');
                }

                // Get each component of the address from the place details,
                // and then fill-in the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i].long_name;
                        regex = new RegExp("("+val+")(?!.*\1)", "gi");
                        if(addressType === 'country') {
                            $('#country').val($('#country option:contains('+val+')').first().val());
                            loca = loca.replace(regex, '');
                        } else {
                            addr = $('#'+componentForm[addressType]).val() ? $('#'+componentForm[addressType]).val() + ' ' : '';
                            $('#'+componentForm[addressType]).val(addr + val);
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
            });
        }
        $(document).ready(function(){

            $("#google-address").on("keyup",function(){
                google.maps.event.addDomListener(window, 'load', initialize());
                initialize();


            });


        var countries = <?php echo getCountryListObject() ?>;
        var states = <?php echo AusStates() ?>;

            var options = '';
            var options1 = '';
            var id_type_sender = "{{$sender->id_type}}";
            var issued_by_sender = "{{$sender->issued_by}}";

            $.each(countries, function (key, val) {

                if (issued_by_sender == val.name) {
                    options += '<option value="'+val.name +'" selected="selected">'+(val.name === "Nepal" ? "Nepal,MOFA" : val.name ) +'</option>';

                }else{
                    if(val.id == 154){
                        selec = 'selected';
                    }else{
                        selec='';
                    }
                    options += '<option value="'+val.name+'" '+selec+'>'+(val.name === "Nepal" ? "Nepal,MOFA" : val.name ) +'</option>';
                }
            });
            $.each(states, function (key, val) {

                if (issued_by_sender == val.name) {
                    options1 += '<option value="'+val.name +'" selected="selected">'+val.name +'</option> <br>';
                }else{

                    options1 += '<option value="'+val.name+'" >'+val.name +'</option> <br>';
                }


            });

        @if(isset($sender))

            if (id_type_sender == 1) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options+'</select>');


            } else if (id_type_sender == 2) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');

            } else if (id_type_sender == 3) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');
            }

                @endif

        $("#id_type").change(function () {
            var type = $(this).val();


            // var options = '';
            // var options1 = '';
            // $.each(countries, function (key, val) {
            //     // alert(key + val);
            //     if(val.id == 154){
            //         selec = 'selected';
            //     }else{
            //         selec='';
            //     }
            //     options += '<option value="'+val.name+'" '+selec+'> '+val.name +'</option> <br>';
            //
            //
            // });  $.each(states, function (key, val) {
            //
            //     // alert(key + val);
            //     options1 += '<option value="'+val.name+'" > '+val.name +'</option> <br>';
            //
            //
            // });

            if (type == 1) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options+'</select>');


            } else if (type == 2) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');

            } else if (type == 3) {
                $("#issued_by_diiv").html('<select class="form-control" name="issued_by">'+options1+'</select>');
            }
        })
        })
        </script>
    @stop
