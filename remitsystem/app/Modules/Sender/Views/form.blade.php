<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <strong>General Information</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                        <label class="control-label">First Name<span class="asterik-red" > (*)</span></label>
                        {!!Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'placeholder'=>'Enter First Name', 'pattern'=>'[a-zA-Z ]*'))!!}
                        @if($errors->has('first_name'))
                            {!! $errors->first('first_name', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                        <label class="control-label">Last Name<span class="asterik-red" > (*)</span></label>
                        {!!Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'placeholder'=>'Enter Last Name', 'pattern'=>'[a-zA-Z ]*'))!!}
                        @if($errors->has('last_name'))
                            {!! $errors->first('last_name', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('number')) {{'has-error'}} @endif">
                        <label class="control-label">Phone Number<span class="asterik-red" > (*)</span></label>
                        {!!Form::number('number', null, array('class' => 'form-control', 'id'=>'number','placeholder'=>'Enter Phone Number', 'pattern'=>'[0-9]{10}'))!!}
                        @if($errors->has('number'))
                            {!! $errors->first('number', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('email')) {{'has-error'}} @endif">
                        <label class="control-label">Email<span class="asterik-red" > (*)</span></label>
                        {!!Form::email('email', null, array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Enter Email Address'))!!}
                        @if($errors->has('email'))
                            {!! $errors->first('email', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                        <span class="text-red" id="email-exist-msg"></span>
                    </div>
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
                    <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                        <label class="control-label">Street<span class="asterik-red" > (*)</span></label>
                        {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street','placeholder'=>'Enter Street'))!!}
                        @if($errors->has('street'))
                            {!! $errors->first('street', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6" id="sender-col">
                    <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                        <label class="control-label">Suburb<span class="asterik-red" > (*)</span></label>
                        {!!Form::select('suburb', $suburb, isset($sender) ? $sender->suburb : null, array('class' => 'form-control','placeholder'=>'Choose Suburb', 'id'=>'suburb'))!!}
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
                        {!!Form::select('state', australiaStateLists(), null, array('class' => 'form-control','placeholder'=>'Choose State', 'id'=>'state'))!!}
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
                        <label>Address Proof <span class="asterik-red">(*)</span> [max size : 10MB]</label>
                        <div>
                            Phone bill, photo id, electricity bill, gas bill, bank statement etc
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
    <div class="card">
        <div class="card-header">
            <strong>Identification Details</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif">
                        <label class="control-label">Id Type<span class="asterik-red" > (*)</span></label>
                        {!!Form::select('id_type', identificationTypes(),old('id_type'), array('class' => 'form-control', 'id'=>'id_type','placeholder'=>'Please select'))!!}
                        @if($errors->has('id_type'))
                            {!! $errors->first('id_type', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Issued By<span class="asterik-red" > (*)</span></label>

                    <div class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif" id="issued_by_diiv">
                        @if(isset($sender))
                            <select name="issued_by" class="form-control"><option value="{{$sender->issued_by}}">{{$sender->issued_by}}</option></select>
                        @else
                            <select name="issued_by" class="form-control"><option ></option></select>
                        @endif

                        @if($errors->has('issued_by'))
                            {!! $errors->first('issued_by', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('id_number')) {{'has-error'}} @endif">
                        <label class="control-label">Id Number<span class="asterik-red" > (*)</span></label>
                        {!!Form::text('id_number', null, array('class' => 'form-control', 'id'=>'id_number', 'placeholder'=>'Enter ID Number'))!!}
                        @if($errors->has('id_number'))
                            {!! $errors->first('id_number', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <fieldset class="form-group @if($errors->has('expiry_date')) {{'has-error'}} @endif">
                        <label >Expiry Date<span class="asterik-red" > (*)</span></label>
                        <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </span>
                            @if(isset($sender) && $sender->expiry_date != '')
                                {!!Form::text('expiry_date', standard_date($sender->expiry_date), array('class' => 'form-control', 'id'=>'expiry_date', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            @else
                                {!!Form::text('expiry_date', null, array('class' => 'form-control', 'id'=>'expiry_date', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            @endif
                        </div>

                    </fieldset>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <fieldset class="form-group @if($errors->has('dob')) {{'has-error'}} @endif">
                        <label class="control-label">Date of Birth<span class="asterik-red" > (*)</span></label>
                        <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </span>
                            @if(isset($sender) && $sender->dob != '')
                                {!!Form::text('dob', standard_date($sender->dob), array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            @else
                                {!!Form::text('dob', null, array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            @endif
                        </div>
                    </fieldset>
                    @if($errors->has('dob'))
                        {!! $errors->first('dob', '<label class="control-label text-danger"
                                                                for="inputError">:message</label>') !!}
                    @endif
                </div>
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('service_charge')) {{'has-error'}} @endif">

                        {!! Form::label('service_charge','Service Charge [leave empty if not a special sender]', ['class' => 'control-label']) !!}

                        <div>
                            {!!Form::number('service_charge', null, array('class' => 'form-control', 'id'=>'service_charge','step'=>'0.01','autocomplete' => 'off'))!!}

                        </div>
                        @if($errors->has('service_charge'))
                            {!! $errors->first('service_charge', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif  </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('image')) {{'has-error'}} @endif">
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                            {!! Form::label('image','Upload Identification [10 MB max]', ['class' => 'control-label']) !!}
                        @else
                            {!! Form::label('image','Upload Identification [10 MB max]', ['class' => 'control-label']) !!}
                        @endif
                        <div>
                            {!!  Form::file('image')  !!}
                        </div>
                        @if($errors->has('image'))
                            {!! $errors->first('image', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif  </div>
                </div>
                @if(isset($sender))
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('image')) {{'has-error'}} @endif">
                        {!! Form::label('added_by','Added By', ['class' => 'control-label']) !!}
                {!!Form::select('added_by', get_users_list(), $sender->added_by, array('class' => 'form-control', 'id'=>'added-by'))!!}
                    </div>
            </div>
                    @endif
        </div>
    </div>
    @if(isset($modal)== true)
        <input type="hidden" value="true" name="modal">
    @endif
</div>
</div>
@section('page-script')
@parent
<script>

    $(document).ready(function () {
        var date = new Date();
        var dob = new Date();
        dob.setTime(dob.getTime()-(16*365*24*60*60*1000));
        $("#dob").mask("99/99/9999");
        $("#expiry_date").mask('99/99/9999');
        $('input[name="dob"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            maxDate: dob,
        });
        $('input[name="dob"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });

        $('input[name="dob"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
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

        $('input[name="expiry_date"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });

        $('input[name="expiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $('#added-by').select2({

            theme: "bootstrap"
        });
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

    });
    var oldSuburb = "{{ old('suburb') }}";
    if (oldSuburb) {
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

</script>

@endsection
