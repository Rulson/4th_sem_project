@extends('layouts.main')
@section('title', 'Add Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('distributors.index')}}" title="All Distributors">Distributors</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop

@section('content')

            <form method="post" action="{{route('distributor.store')}}">
            {{ csrf_field() }}
            <!-- /.box-header -->
                <div class="animated fadeIn">
                    <div class="col-sm-12">

                        <div class="card">
                            <div class="card-header">
                                <strong>Add Distributor</strong>
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
                                            <label>Company Name <span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="company_name" class="form-control" id="companyName"
                                                   placeholder="Enter Company Name" value="{{old('company_name')}}">
                                        </div>
                                        {!! $errors->first('company_name', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="asterik-red" > (*)</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Enter Email" value="{{old('email')}}">
                                        </div>
                                        {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number <span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="phone_number" class="form-control"
                                                   id="phoneNumber" value="{{old('phone_number')}}"
                                                   placeholder="Enter Phone Number">
                                        </div>
                                        {!! $errors->first('phone_number', '<p class="text-danger">:message</p>') !!}

                                    </div>


                                </div>
                                    </div>
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
                                                   placeholder="Type Google address here"   >

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Suburb <span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="street" value="{{old('street')}}" class="form-control" id="street"
                                                   placeholder="Enter Suburb">

                                        </div>
                                        {!! $errors->first('street', '<p class="text-danger">:message</p>') !!}

                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District<span class="asterik-red" > (*)</span></label>
                                            <input type="text" name="suburb" class="form-control" id="suburb" value="{{old('suburb')}}"
                                                   placeholder="Enter District">
                                        </div>
                                        {!! $errors->first('suburb', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State / Province <span class="asterik-red" > (*)</span></label>
                                            {!!Form::text('state', null, array('class' => 'form-control', 'id'=>'state', 'placeholder'=>'Enter State'))!!}
                                        </div>
                                        {!! $errors->first('state', '<p class="text-danger">:message</p>') !!}

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Post Code<span class="asterik-red" > (*)</span></label>
                                            <input type="text" class="form-control"  name="post_code" id="postcode"
                                                   placeholder="Enter Post Code">
                                        </div>
                                        {!! $errors->first('post_code', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country <span class="asterik-red" > (*)</span></label>
                                            <select name="country"
                                                    class="form-control select2-state select2-hidden-accessible"
                                                    style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                @foreach(getCountryList() as $key=>$value)
                                                    <option value="{{$key}}" {{$key == 154 ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>

                                <div class="card">
                                    <div class="card-header">
                                        <strong>Bank Details</strong>
                                    </div>
                                    <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Name</label>
                                            <input type="text" name="account_name" class="form-control" id="accountName"
                                                   placeholder="Enter Account Name" value="{{old('account_name')}}">
                                        </div>
                                        {!! $errors->first('account_name', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input type="text" class="form-control" name="account_no" id="accountNo"
                                                   placeholder="Enter Account Number" value="{{old('account_no')}}">
                                        </div>
                                        {!! $errors->first('account_no', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>BSB / Branch</label>
                                            <input type="text" name="bsb" class="form-control"
                                                   id="bsb" value="{{old('bsb')}}"
                                                   placeholder="Enter BSB / Branch">
                                        </div>
                                        {!! $errors->first('bsb', '<p class="text-danger">:message</p>') !!}

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            {!!Form::select('bank_name', BankList(), null, array('class' => 'form-control', 'id'=>'bank_name','placeholder'=>'Please Select'))!!}
                                        </div>
                                        {!! $errors->first('bank_name', '<p class="text-danger">:message</p>') !!}

                                    </div>


                                </div>

                          </div>
                            <!-- /.box-body -->
                        </div>
                               <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Save</button>
            </div>
                        </div>
                    </div>
                </div>

            </form>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvr5QYO9Ev2aVLO4WqXOUTuJAD8rA8YZU&libraries=places&callback=initialize"
                    async defer></script>
<script>
    $(document).ready(function(){
        function initialize() {


            var input = document.getElementById('google-address');

            var componentForm = {
                route: 'street',
                sublocality_level_2: 'street',
                sublocality_level_1: 'street',
                locality: 'suburb',
                administrative_area_level_1: 'state',
                country: 'country_list_id',
                postal_code: 'postcode'
            };

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                //   console.log(place);
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
                /*for (var component in componentForm) {
                    $('#'+component).val('');
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

    });
</script>

@endsection
@section('page-script')
<script>
    $('.select2-state').select2({
        theme:"bootstrap"
    });
</script>
    @endsection