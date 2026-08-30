<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <strong>User</strong>
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
                    <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                        <label>First Name<span class="asterik-red" > (*)</span></label>
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                            {!!Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'placeholder'=>'Enter First Name'))!!}
                        @else
                            {!!Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'placeholder'=>'Enter First Name','readonly'))!!}
                        @endif                       @if($errors->has('first_name'))
                            {!! $errors->first('first_name', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                        <label>Last Name<span class="asterik-red" > (*)</span></label>
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                            {!!Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'placeholder'=>'Enter Last Name'))!!}
                        @else            {!!Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'placeholder'=>'Enter Last Name','readonly'))!!}
                        @endif       @if($errors->has('last_name'))
                            {!! $errors->first('last_name', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                   <fieldset class="form-group @if($errors->has('dob')) {{'has-error'}} @endif">

                        <label>Date Of Birth <span class="asterik-red" > (*)</span></label>
                        <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                            @if(isset($user) && $user->dob != '')
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                    {!!Form::text('dob', standard_date($user->dob), array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'mm/dd/yyyy', 'autocomplete' => 'off'))!!}
                                @else
                                    {!!Form::text('dob', standard_date($user->dob), array('class' => 'form-control','id'=>'dob-edit', 'placeholder'=>'mm/dd/yyyy', 'autocomplete' => 'off','readonly'))!!}
                                @endif
                            @else
                                {!!Form::text('dob', null, array('class' => 'form-control', 'id'=>'dob', 'placeholder'=>'mm/dd/yyyy', 'autocomplete' => 'off'))!!}
                            @endif
                        </div>
                        @if($errors->has('dob'))
                            {!! $errors->first('dob', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('number')) {{'has-error'}} @endif">
                        <label>Phone Number<span class="asterik-red" > (*)</span></label>
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                            {!!Form::text('number', null, array('class' => 'form-control', 'id'=>'number', 'placeholder'=>'Enter Phone Number'))!!}
                        @else
                            {!!Form::text('number', null, array('class' => 'form-control', 'id'=>'number', 'placeholder'=>'Enter Phone Number','readonly'))!!}
                        @endif      @if($errors->has('number'))
                            {!! $errors->first('number', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
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
                    <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                        {!!Form::label('suburb', 'Suburb', array('class' => 'control-label')) !!}
                        {!!Form::text('suburb', null, array('class' => 'form-control', 'id'=>'suburb', 'placeholder'=>'Enter Suburb'))!!}
                        @if($errors->has('suburb'))
                            {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif">
                        <label>State<span class="asterik-red" > (*)</span></label>
                        {!!Form::select('state', australiaStateLists(), null, array('class' => 'form-control', 'id'=>'state','placeholder'=>'Choose State'))!!}
                        @if($errors->has('state'))
                            {!! $errors->first('state', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                        {!!Form::label('street', 'Street', array('class' => 'control-label')) !!}
                        {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street', 'placeholder'=>'Enter Street'))!!}
                        @if($errors->has('street'))
                            {!! $errors->first('street', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif">
                        {!!Form::label('postcode', 'Postal Code', array('class' => 'control-label')) !!}
                        @if(isset($user))
                            {!!Form::text('postcode', null, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code'))!!}
                        @else
                            {!!Form::text('postcode', 00, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code'))!!}

                        @endif
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
                        <label>Country<span class="asterik-red" > (*)</span></label>
                            {!!Form::select('country_list_id', $countries, 13, array('class' => 'form-control', 'id'=>'country'))!!}
                        </div>
                    </div>

                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong>Login Details</strong>

                </div>
                <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('email')) {{'has-error'}} @endif">
                        <label>Email Address<span class="asterik-red" > (*)</span></label>
                        {!!Form::email('email', null, array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Enter Email Address'))!!}
                        @if($errors->has('email'))
                            {!! $errors->first('email', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                @if(!in_array(Auth::user()->level_id, [3,7,8]))
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role<span class="asterik-red" > (*)</span></label>
                        @if(isset($user))
                            {!!Form::select('level_id', $user_roles, $user->levelid, array('class' => 'form-control', 'id'=>'level_id'))!!}
                        @else
                            {!!Form::select('level_id', $user_roles, null, array('class' => 'form-control', 'id'=>'level_id'))!!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">

                @if(isset($user))
                    @if(in_array(Auth::user()->level_id, [1,2]))

                        <div class="col-md-6 application-block"  style="display: @if($user->levelid == 5) block @else none @endif ">
                            <div class="form-group">
                                <label>Application<span class="asterik-red" > (*)</span></label>

                                {!!Form::select('application_id', getApplicationList(), null, array('class' => 'form-control', 'id'=>'application_id'))!!}

                            </div>
                        </div>
                    @endif
                @endif
            @endif

                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('password')) {{'has-error'}} @endif">
                                @if(isset($user))
                                    <label class="control-label">New Password<span class="asterik-red" > (*) </span>[Leave Empty if you don't want to change the password]</label>
                                   @else
                                    <label class="control-label">New Password <span class="asterik-red" > (*)</span></label>
                                @endif
                                {!!Form::password('password', array('class' => 'form-control', 'id'=>'password'))!!}
                                @if($errors->has('password'))
                                    {!! $errors->first('password', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('confirm_password')) {{'has-error'}} @endif">
                                <label class="control-label">Confirm Password <span class="asterik-red" > (*)</span></label>
                                {!!Form::password('confirm_password', array('class' => 'form-control', 'id'=>'confirm_password'))!!}
                                @if($errors->has('confirm_password'))
                                    {!! $errors->first('confirm_password', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
</div>
</div>

@section('page-script')
    <script>
        $(document).ready(function () {
            var edit = "{{isset($user) ? 'true' : 'false' }}";

            if(edit === 'true'){
                $('body').on('change','#level_id',function () {
                   var level_id= $(this).val();
                   if(level_id == 5){
                       $('.application-block').css('display','block');
                   }
                   else{
                       $('.application-block').css('display','none');
                   }
                });
            }
            var date = new Date();
            var dob = new Date();
            dob.setTime(dob.getTime()-(16*365*24*60*60*1000));
            $('input[name="dob"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                maxDate: dob
            });
            $('input[name="dob"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
            });

            $('input[name="dob"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
    </script>
@stop
