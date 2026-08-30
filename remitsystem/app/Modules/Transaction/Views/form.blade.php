<div class="row">
    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">General Information</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                            {!!Form::label('first_name', 'First Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'placeholder'=>'Enter First Name'))!!}
                            @if($errors->has('first_name'))
                                {!! $errors->first('first_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                            {!!Form::label('last_name', 'Last Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'placeholder'=>'Enter Last Name'))!!}
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
                            {!!Form::label('number', 'Phone Number (*)', array('class' => 'control-label')) !!}
                            {!!Form::tel('number', null, array('class' => 'form-control', 'id'=>'number', 'placeholder'=>'Enter Phone Number'))!!}
                            @if($errors->has('number'))
                                {!! $errors->first('number', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('email')) {{'has-error'}} @endif">
                            {!!Form::label('email', 'Email (*)', array('class' => 'control-label')) !!}
                            {!!Form::email('email', null, array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Enter Email Address'))!!}
                            @if($errors->has('email'))
                                {!! $errors->first('email', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Address Information</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                            {!!Form::label('street', 'Street (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street', 'placeholder'=>'Enter Street'))!!}
                            @if($errors->has('street'))
                                {!! $errors->first('street', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                            {!!Form::label('suburb', 'Suburb (*)', array('class' => 'control-label')) !!}
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
                        <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif">
                            {!!Form::label('state', 'State (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('state', null, array('class' => 'form-control', 'id'=>'state', 'placeholder'=>'Enter State'))!!}
                            @if($errors->has('state'))
                                {!! $errors->first('state', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif">
                            {!!Form::label('postcode', 'Postal Code (*)', array('class' => 'control-label')) !!}
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
                            {!!Form::label('country_list_id', 'Country (*)', array('class' => 'control-label')) !!}
                            {!!Form::select('country_list_id', $countries, 13, array('class' => 'form-control', 'id'=>'country'))!!}
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
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Identification Details</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif">
                            {!!Form::label('id_type', 'Id Type (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('id_type', null, array('class' => 'form-control', 'id'=>'id_type', 'placeholder'=>'Enter ID Type'))!!}
                            @if($errors->has('id_type'))
                                {!! $errors->first('id_type', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif">
                            {!!Form::label('issued_by', 'Issued By (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('issued_by', null, array('class' => 'form-control', 'id'=>'issued_by', 'placeholder'=>'Enter Issued By'))!!}
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
                            {!!Form::label('id_number', 'Id Number (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('id_number', null, array('class' => 'form-control', 'id'=>'id_number', 'placeholder'=>'Enter Postal Code'))!!}
                            @if($errors->has('id_number'))
                                {!! $errors->first('id_number', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('expiry_date')) {{'has-error'}} @endif">
                            {!!Form::label('expiry_date', 'Expiry Date (*)', array('class' => 'control-label')) !!}
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>

                                @if(isset($user) && $user->expiry_date != '')
                                    {!!Form::text('expiry_date', format_date($user->expiry_date), array('class' => 'form-control', 'id'=>'expiry_date', 'placeholder'=>'Enter DOB', 'autocomplete' => 'off'))!!}
                                @else
                                    {!!Form::text('expiry_date', null, array('class' => 'form-control', 'id'=>'expiry_date', 'placeholder'=>'Enter Expiry Date', 'autocomplete' => 'off'))!!}
                                @endif
                            </div>
                            @if($errors->has('expiry_date'))
                                {!! $errors->first('expiry_date', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('dob')) {{'has-error'}} @endif">
                            {!!Form::label('dob', 'Date Of Birth', array('class' => 'control-label')) !!}
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>

                                @if(isset($user) && $user->dob != '')
                                    {!!Form::text('dob', format_date($user->dob), array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'Enter DOB', 'autocomplete' => 'off'))!!}
                                @else
                                    {!!Form::text('dob', null, array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'Enter DOB', 'autocomplete' => 'off'))!!}
                                @endif
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
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Company Details (Only for company)</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('company_name')) {{'has-error'}} @endif">
                            {!!Form::label('company_name', 'Company Name', array('class' => 'control-label')) !!}
                            {!!Form::text('company_name', null, array('class' => 'form-control', 'id'=>'company_name', 'placeholder'=>'Enter Company Name'))!!}
                            @if($errors->has('company_name'))
                                {!! $errors->first('company_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('abn')) {{'has-error'}} @endif">
                            {!!Form::label('abn', 'ABN', array('class' => 'control-label')) !!}
                            {!!Form::text('abn', null, array('class' => 'form-control', 'id'=>'abn', 'placeholder'=>'Enter First Name'))!!}
                            @if($errors->has('abn'))
                                {!! $errors->first('abn', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('notes')) {{'has-error'}} @endif">
                            {!!Form::label('notes', 'Notes', array('class' => 'control-label')) !!}
                            {!!Form::textarea('notes', null, array('class' => 'form-control', 'id'=>'notes'))!!}
                            @if($errors->has('notes'))
                                {!! $errors->first('notes', '<label class="control-label text-danger"
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


@section('page-script')
    <script>
        $(document).ready(function () {
            var date = new Date();
            $("#dob").datepicker({
                autoclose: true,
                format: 'dd-M-yyyy',
                endDate: date
            });

            $("#expiry_date").datepicker({
                autoclose: true,
                format: 'dd-M-yyyy',
                startDate: date
            });
        });
    </script>
@stop