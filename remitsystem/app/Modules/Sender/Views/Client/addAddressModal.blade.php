<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Add Address</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => ['sender.store.address',$senderId],'method' => 'post'))!!}

<div class="modal-body">

<div class="row">
    <div class="col-md-12">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('street')) {{'is-invalid'}} @endif" id="street_div">
                            {!!Form::label('street', 'Street (*)', array('class' => 'control-label','id'=>'street-label')) !!}
                            {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street', 'placeholder'=>'Enter Street'))!!}
                            @if($errors->has('street'))
                                {!! $errors->first('street', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif" id="suburb_div">
                            {!!Form::label('suburb', 'Suburb (*)', array('class' => 'control-label', 'id'=>'suburb-label')) !!}
                            {!!Form::text('suburb', null, array('class' => 'form-control', 'id'=>'suburb', 'placeholder'=>'Enter Suburb'))!!}
                            @if($errors->has('suburb'))
                                {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif"  id="state_div">
                            {!!Form::label('state', 'State (*)', array('class' => 'control-label', 'id'=>'state-label')) !!}
                            {!!Form::select('state', australiaStateLists(), null, array('class' => 'form-control','placeholder'=>'choose state', 'id'=>'state'))!!}

                            @if($errors->has('state'))
                                {!! $errors->first('state', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif" id="postcode_div">
                            {!!Form::label('postcode', 'Postal Code (*)', array('class' => 'control-label', 'id'=>'postcode-label')) !!}
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
                        <div class="form-group" id="country_div">
                            {!!Form::label('country_list_id', 'Country (*)', array('class' => 'control-label')) !!}
                            {!!Form::select('country_list_id', getCountryList(),13, array('class' => 'form-control', 'id'=>'country'))!!}
                        </div>
                    </div>
                </div>

    </div>
    <div class="col-md-12">
    <button id="save-new-address" class="btn btn-primary pull-right">Save</button>
    </div>

</div>
<script>
    $("#save-new-address").click(function () {
          /*  var street_div = $("#street_div");
            var suburb_div = $("#suburb_div");
            var state_div = $("#state_div");
            var postcode_div = $("#postcode_div");
            var country_div = $("#country_div");*/
            if ($("#street").val() === '') {
               // street_div.addClass("has-error");
                $("#street").addClass("is-invalid");
                $("#street-label").addClass("text-danger");
                return false;
            }else{
              //  street_div.removeClass("has-error");
                $("#street").removeClass("is-invalid");
                $("#street-label").removeClass("text-danger");

            }
            if ($("#suburb").val() === '') {
              //  suburb_div.addClass("has-error");
                $("#suburb").addClass("is-invalid");
                $("#suburb-label").addClass("text-danger");
                return false;
            }else{
             //   suburb_div.removeClass("has-error");
                $("#suburb").removeClass("is-invalid");
                $("#suburb-label").removeClass("text-danger");

            }
            if ($("#state").val() === '') {
             //   state_div.addClass("has-error");
                $("#state").addClass("is-invalid");
                $("#state-label").addClass("text-danger");
                return false;
            }else{
              //  state_div.removeClass("has-error");
                $("#state").removeClass("is-invalid");
                $("#state-label").removeClass("text-danger");

            }
            if ($("#postcode").val() === '') {
               // postcode_div.addClass("has-error");
                $("#postcode").addClass("is-invalid");
                $("#postcode-label").addClass("text-danger");
                return false;
            }else{
              //  postcode_div.removeClass("has-error");
                $("#postcode").removeClass("is-invalid");
                $("#postcode-label").removeClass("text-danger");

            } if ($("#country_list_id").val() === '') {
              //  country_div.addClass("has-error");
            $("#country_list_id").addClass("is-invalid");
                return false;
            }else{
             //   country_div.removeClass("has-error");
            $("#country_list_id").removeClass("is-invalid");

            }
            $('#save-new-address').submit();

        }
    );

</script>
