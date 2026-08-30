<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Coupon</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('name')) {{'has-error'}} @endif">
                            {!!Form::label('name', 'Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'placeholder'=>'Enter name'))!!}
                            @if($errors->has('name'))
                                {!! $errors->first('name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('code')) {{'has-error'}} @endif">
                            {!!Form::label('code', 'Code (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('code', null, array('class' => 'form-control', 'id'=>'code', 'placeholder'=>'Enter Code'))!!}
                            @if($errors->has('code'))
                                {!! $errors->first('code', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('discount_value')) {{'has-error'}} @endif">
                            {!!Form::label('discount_value', 'Discount Value (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('discount_value', null, array('class' => 'form-control', 'id'=>'discount_value', 'placeholder'=>'Enter Discount Value'))!!}
                            @if($errors->has('discount_value'))
                                {!! $errors->first('discount_value', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('discount_unit')) {{'has-error'}} @endif">
                            {!!Form::label('discount_unit', 'Discount Unit (*)', array('class' => 'control-label')) !!}
                            {{ Form::select('discount_unit', ['f' => 'Fixed', 'p' => 'Percentage'],  null, ['id' => 'type','class'=>'form-control','placeholder'=>'Select Discount Unit']) }}
                            @if($errors->has('discount_unit'))
                                {!! $errors->first('discount_unit', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('application_id')) {{'has-error'}} @endif">
                            {!!Form::label('application_id', 'Application (*)', array('class' => 'control-label')) !!}
                            {{ Form::select('application_id', getApplicationList(),  null, ['id' => 'application_id','class'=>'form-control','placeholder'=>'Select Application']) }}
                            @if($errors->has('application_id'))
                                {!! $errors->first('application_id', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('user_type')) {{'has-error'}} @endif">
                            {!!Form::label('user_type', 'User Type(*)', array('class' => 'control-label')) !!}
                            {{ Form::select('user_type', ['0'=>'All','1'=>'Old User','2'=>'New User'],  null, ['id' => 'user_type','class'=>'form-control','placeholder'=>'Select User Type']) }}
                            @if($errors->has('user_type'))
                                {!! $errors->first('user_type', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">

                        <fieldset class="form-group @if($errors->has('dob')) {{'has-error'}} @endif">
                            <label class="control-label">Start Date (*)</label>
                            <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                {!!Form::text('start_date',isset($coupon)? standard_date($coupon->start_date): null, array('class' => 'form-control', 'id'=>'start_date', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            </div>

                        </fieldset>
                        @if($errors->has('start_date'))
                            {!! $errors->first('start_date', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                    <div class="col-md-6">
                        <fieldset class="form-group @if($errors->has('end_date')) {{'has-error'}} @endif">
                            <label class="control-label">End Date (*)</label>
                            <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                {!!Form::text('end_date',(isset($coupon) && $coupon->end_date != null)? standard_date($coupon->end_date): null, array('class' => 'form-control', 'id'=>'end_date', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off'))!!}
                            </div>

                        </fieldset>
                        @if($errors->has('end_date'))
                            {!! $errors->first('end_date', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('uses_total')) {{'has-error'}} @endif">
                            {!!Form::label('uses_total', 'Uses Total (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('uses_total', (!isset($coupon))?0:null, array('class' => 'form-control', 'id'=>'uses_total', 'placeholder'=>'Enter Uses Total'))!!}
                            @if($errors->has('uses_total'))
                                {!! $errors->first('uses_total', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('published', 'Published',["class" => "radiostatus"]) !!}
                            <label class="radio-inline"> {!! Form::radio('published', 1, true) !!}Yes </label>
                            <label class="radio-inline"> {!! Form::radio('published', 0, false) !!}No </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>


@section('page-script')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $("#start_date").mask("99/99/9999");
            $("#end_date").mask("99/99/9999");
            $('input[name="start_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format:'DD/MM/YYYY'
                },
                minYear: 1901,
            });
            $('input[name="start_date"]').on('apply.daterangepicker', function (ev, picker) {
                //  $(this).val(picker.startDate.format('DD-MMM-YYYY'));
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
                console.log(picker.startDate.format('DD/MM/YYYY'))
            });

            $('input[name="start_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            $('input[name="end_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format:'DD/MM/YYYY'
                },
                minYear: 1901,
            });
            $('input[name="end_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });

            $('input[name="end_date "]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        })



    </script>
@endsection
