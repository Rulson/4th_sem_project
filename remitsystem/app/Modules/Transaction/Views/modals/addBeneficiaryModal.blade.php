<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Add Beneficiary</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
</div>
<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>

{!!Form::open(array('route' => ['beneficiary.store'], 'id' => 'add-beneficiary', 'method' => 'post'))!!}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" id="sender_id" name="sender_id" value="{{$sender_id}}">
            <div class="card">
                <div class="card-header">
                    <strong>General Information</strong>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">First Name<span class="asterik-red" > (*)</span></label>

                                <input type="text" name="first_name" class="form-control" id="first_name"
                                       placeholder="Enter First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Last Name<span class="asterik-red" > (*)</span></label>

                                <input type="text" name="last_name" class="form-control" id="last_name"
                                       placeholder="Enter Last Name">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Phone Number<span class="asterik-red" > (*)</span></label>

                                <input type="text" name="number" class="form-control"
                                       id="number"
                                       placeholder="Enter Phone Number">
                            </div>
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
                    <strong>Address Information</strong>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" >
                                <label class="control-label">Suburb<span class="asterik-red" > (*)</span></label>

                                <input type="text" name="street" class="form-control" id="street"
                                       placeholder="Enter Suburb">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Country<span class="asterik-red" > (*)</span></label>

                                {!!Form::select('country_list_id', $countries, 154, array('class' => 'form-control', 'id'=>'country'))!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">District<span class="asterik-red" > (*)</span></label>
                                {!!Form::select('suburb', $data['district'], null, array('class' => 'form-control','placeholder'=>'Choose district', 'id'=>'suburb'))!!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Post Code<span class="asterik-red" > (*)</span></label>

                                {!!Form::text('postcode',977, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code','readonly'))!!}
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
                                <label class="control-label">State/Province<span class="asterik-red" > (*)</span></label>

                                {!!Form::select('state', $data['np_state'], null, array('class' => 'form-control','placeholder'=>'Choose Province', 'id'=>'np_state'))!!}
                                @if($errors->has('state'))
                                    {!! $errors->first('state', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>
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
                    <strong>Account Details</strong>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                <label>Account Name </label>
                                <input type="text" name="account_name" class="form-control" id="account_name"
                                       placeholder="Enter Account Name">
                            </div>
                            @if($errors->has('account_name'))
                                {!! $errors->first('account_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                <label>Account Number </label>
                                <input type="text" name="account_no" class="form-control" id="account_no"
                                       placeholder="Enter Account Number">
                            </div>
                            @if($errors->has('account_no'))
                                {!! $errors->first('account_no', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('bsb')) {{'has-error'}} @endif">
                                <label> Branch </label>
                                <input type="text" name="bsb" class="form-control"
                                       id="bsb"
                                       placeholder="Enter Branch">
                            </div>
                            @if($errors->has('bsb'))
                                {!! $errors->first('bsb', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('bank_name')) {{'has-error'}} @endif">
                                <label>Bank Name </label>
                                {!!Form::select('bank_name', BankList(), null, array('class' => 'form-control', 'id'=>'bank_name','placeholder'=>'Please Select'))!!}

                            </div>
                            @if($errors->has('bank_name'))
                                {!! $errors->first('bank_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" id="add-beneficiaryPopUp" class="btn btn-primary">Save</button>
</div>
{!!Form::close()!!}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
        async defer></script>


<script>
    $('#bank_name,#np_state,#suburb').select2({

        theme: "bootstrap"
    });
    $(document).ready(function(){

        function initialize() {


            var input = document.getElementById('google-address');

            var componentForm = {
              //  neightbourhood:'street',
               // political:"street",
                route: 'street',
                sublocality_level_2: 'street',
                sublocality_level_1: 'street',
                locality: 'suburb',
                administrative_area_level_1: 'state',
                country: 'country_list_id',
                postal_code: 'postcode'
            };
            var options = {
                componentRestrictions: {country: "np"}
            };

            var autocomplete = new google.maps.places.Autocomplete(input,options);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
               console.log(place);
                console.log(place.address_components);
                // place variable will have all the information you are looking for.
                $('#lat').val(place.geometry['location'].lat());
                $('#long').val(place.geometry['location'].lng());
                //var address = place.address_components;
              //  var value = address.split(",");
              //  console.log(value);
             /*   if(address.length == 3){
                    document.getElementById("suburb").value =  address[address.length-3].long_name;
                    document.getElementById("postcode").value = address[address.length-1].long_name;
                    document.getElementById("state").value =  address[address.length-2].long_name;

                }else if(address.length == 4){
                    document.getElementById("street").value =  address[address.length-5].long_name;
                    document.getElementById("suburb").value =  address[address.length-4].long_name;
                    document.getElementById("postcode").value = address[address.length-1].long_name;
                    document.getElementById("state").value =  address[address.length-3].long_name;

                }*/
                for (var [key, value] of Object.entries(componentForm)) {
                   // console.log(key, value);
                    $('#'+value).val('');
                }

                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i].long_name;
                        if(addressType === 'country') {
                            $('#country').val($('#country option:contains('+val+')').first().val());
                        } else {
                            addr = $('#'+componentForm[addressType]).val() ? $('#'+componentForm[addressType]).val() + ',' : '';
                            $('#'+componentForm[addressType]).val(addr + val);
                        }
                    }
                }

                /*document.getElementById("street").value =  address[0].long_name;
                document.getElementById("suburb").value =  address[address.length-3].long_name;
                document.getElementById("postcode").value = address[address.length-1].long_name;
                document.getElementById("state").value =  address[address.length-4].long_name;*/


/*


                //  console.log(value);
                count = value.length;
                country = value[count - 1];

                state = value[count-2];
                street = value[count-3];
                document.getElementById("street").value = street;
                $("#bsb").val(street);
                //    console.log(country,state,street);
                var z = state.split(" ");
                //   document.getElementById("selCountry").text = country;
                var i = z.length;
                document.getElementById("suburb").value = z[1];
                if (i > 2) {
                    document.getElementById("postcode").value = z[2];
                }
                if (i > 3) {
                    document.getElementById("postcode").value = z[3];
                }
*/
            });
        }

        $("#google-address").on("keyup",function(){

            google.maps.event.addDomListener(window, 'load', initialize());
            initialize();
        });
        $("#first_name,#last_name ").on("keyup",function(){
            var f_name = $("#first_name").val();
            var l_name = $("#last_name").val();
            var f_name = f_name + ' '+ l_name;
            if(f_name != '' && l_name != ''){
                $("#account_name").val(f_name);

            }
        });
        // $('#suburb').on('change', function (e) {
        //     var id = $(this).val();
        //     getState(id);
        // })

        function getState(id){
            var url = "{{ url('beneficiary/getState') }}"+'/'+id;
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                encode: true,
                success:function(data)
                {
                    state = data['selected_state']['name'];
                    $('select[name="state"]').empty();
                    $.each(data['np_state'], function(key,value){
                        if( state == value){
                            $('select[name="state"]').append('<option value="'+ key +'" selected>'+ value +'</option>');
                        }
                        else{
                            $('select[name="state"]').append('<option value="'+ key +'">'+ value +'</option>');
                        }
                    });
                }
            })
        }
    })
</script>
