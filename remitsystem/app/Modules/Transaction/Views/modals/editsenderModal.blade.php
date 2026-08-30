<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title">Edit Sender</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
{!!Form::model($sender, array('route' => array('sender.update', $sender->sender_id),'id'=>'editsenderf', 'class' => '', 'method' => 'POST','files'=>'true'))!!}
<div class="modal-body">

    @include('Sender::form',['modal'=>'true'])
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
{!!Form::close()!!}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
        async defer></script>

<script>

    /*  $('#dob').datepicker({
          format: "d-M-yyyy",
          autoclose: true
      });
      $('#expiry_date').datepicker({
          format: "d-M-yyyy",
          autoclose: true


      });*/
    var countries = <?php echo getCountryListObject() ?>;
    var states = <?php echo AusStates() ?>;
    var value_sel = "{{$sender->issued_by}}";

    $(document).ready(function () {
       // var value_sel = "{{$sender->issued_by}}";
       // var countries = <?php echo getCountryListObject() ?>;
      //  var states = <?php echo AusStates() ?>;
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
            var label = $('#number').next('label.error ');
            label.remove();
            if(phn_num.length !=0){
                if(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num)){
                    return true;
                }
                else{
                    $("#number").after('<label class="error">Please Enter Valid Number.</label>');
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
                        regex = new RegExp("("+val+")(?!.*\1)", "gi");
                        if (addressType === 'country') {
                            $('#country').val($('#country option:contains(' + val + ')').first().val());
                            loca = loca.replace(regex, '');
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

        $("#google-address").on("keyup", function () {

            google.maps.event.addDomListener(window, 'load', initialize());
            initialize();


        });


        $("#dob").mask("99/99/9999");
        $("#expiry_date").mask('99/99/9999');

        var date = new Date();

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


        var options2 = '';
        var options3 = '';
        var selected_val = $("#editsenderf #id_type").val();
        if (selected_val == 1) {

            $.each(countries, function (key, val) {
                // alert(key + val);
                if(value_sel == val.name){
                    selec = 'selected';
                }
                else {
                    if (value_sel == "Nepal") {
                        selec = 'selected';
                    }else{
                        selec = '';
                    }

                }
                options2 += '<option value="' + val.name + '" ' + selec + '> ' + (val.name === "Nepal" ? "Nepal,MOFA" : val.name ) + '</option> <br>';
            });

            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options2 + '</select>');

        } else if (selected_val == 2) {
            $.each(states, function (key, val) {
                if(value_sel == val.name){
                    sel= 'selected'
                }else{
                    sel='';
                }
                options3 += '<option value="' + val.name + '" ' + sel + '> ' + val.name + '</option> <br>';
            });

            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options3 + '</select>');

        } else if (selected_val == 3) {

            $.each(states, function (key, val) {
              //  console.log(val);
                if(value_sel == val.name){
                    selec= 'selected'
                }else{
                    selec='';
                }
                options3 += '<option value="' + val.name + '" '+selec+' > ' + val.name + '</option> <br>';
            });
            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options3 + '</select>');

        } else {
            $.each(states, function (key, val) {

                // alert(key + val);
                options3 += '<option value="' + val.name + '" > ' + val.name + '</option> <br>';
            });
            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options3 + '</select>');

        }
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
                        search: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.response
                    };
                },
            }
        })
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
    $("#editsenderf #id_type").change(function () {
        var type = $(this).val();


        var options = '';
        var options1 = '';
        $.each(countries, function (key, val) {
            // alert(key + val);
            if (val.id == 154) {
                selec = 'selected';
            } else {
                selec = '';
            }
            options += '<option value="' + val.name + '" ' + selec + '> ' + (val.name === "Nepal" ? "Nepal,MOFA" : val.name ) + '</option> <br>';


        });
        $.each(states, function (key, val) {

            // alert(key + val);
            options1 += '<option value="' + val.name + '" > ' + val.name + '</option> <br>';


        });
        if (type == 1) {
            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options + '</select>');
        } else if (type == 2) {
            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options1 + '</select>');

        } else if (type == 3) {
            $("#editsenderf #issued_by_diiv").html('<select class="form-control" name="issued_by">' + options1 + '</select>');
        }
    });

</script>
