<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Beneficiary</strong>
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
                            {!!Form::label('first_name', 'First Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'placeholder'=>'Enter First Name', 'pattern' => '[a-zA-Z ]*'))!!}
                            @if($errors->has('first_name'))
                                {!! $errors->first('first_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                            {!!Form::label('last_name', 'Last Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'placeholder'=>'Enter Last Name', 'pattern' => '[a-zA-Z ]*'))!!}
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

                </div>
            </div>
            <!-- /.box-body -->
        </div>
                <div class="card">
                    <div class="card-header">
                        <strong>Address Information</strong>
                    </div>
                    <div class="card-body">
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
                                <div class="form-group">
                                    <label>Country (*)</label>
                                    {!!Form::select('country_list_id', $countries, 154, array('class' => 'form-control', 'id'=>'country'))!!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                                    {!!Form::label('district', 'District (*)', array('class' => 'control-label')) !!}
                                    {!!Form::select('suburb',$data['district'], null, array('class' => 'form-control','placeholder'=>'Choose district', 'id'=>'suburb'))!!}
                                    @if($errors->has('suburb'))
                                        {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Post Code (*)</label>
                                    @if(isset($beneficiary)) {!!Form::text('postcode',977, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code','readonly'))!!}
                                    @else {!!Form::text('postcode', null, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code'))!!}
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
                                    <label>State / Province (*)</label>
                                    {!!Form::select('state', $data['np_state'], null, array('class' => 'form-control','placeholder'=>'Choose Province', 'id'=>'state'))!!}
                                    @if($errors->has('state'))
                                        {!! $errors->first('state', '<label class="control-label text-danger"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

    </div>
                <div class="card">
                    <div class="card-header">
                        <strong>Account Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                    <label>Account Name </label>
                                    @if(isset($beneficiary))
                                        <input type="text" name="account_name" class="form-control" id="account_name"
                                               placeholder="Enter Account Name" value="{{$beneficiary->account_name}}">
                                    @else
                                        <input type="text" name="account_name" class="form-control" id="account_name"
                                               placeholder="Enter Account Name">
                                    @endif
                                </div>
                                @if($errors->has('account_name'))
                                    {!! $errors->first('account_name', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                    <label>Account Number </label>
                                    @if(isset($beneficiary))
                                        <input type="text" name="account_no" class="form-control" id="account_no"
                                               placeholder="Enter Account Number" value="{{$beneficiary->accountNo}}">
                                    @else<input type="text" name="account_no" class="form-control" id="account_no"
                                                placeholder="Enter Account Number" >
                                    @endif
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
                                    <label>BSB / Branch </label>
                                    @if(isset($beneficiary))
                                        <input type="text" name="bsb" class="form-control"
                                               id="bsb"
                                               placeholder="Enter BSB/Branch" value="{{$beneficiary->bsb}}">
                                    @else   <input type="text" name="bsb" class="form-control"
                                                   id="bsb"
                                                   placeholder="Enter BSB/Branch">
                                    @endif
                                </div>
                                @if($errors->has('bsb'))
                                    {!! $errors->first('bsb', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('bank_name')) {{'has-error'}} @endif">
                                    <label>Bank Name </label>
                                    {!!Form::select('bank_name', BankList(), ($beneficiary)?$beneficiary->bankName : null, array('class' => 'form-control', 'id'=>'bank_name','placeholder'=>'Please Select'))!!}
                                </div>
                                @if($errors->has('bank_name'))
                                    {!! $errors->first('bank_name', '<label class="control-label text-danger"
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
    @parent
    <script>
        $('#bank_name,#np_state,#suburb').select2({

            theme: "bootstrap"
        });
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
        // $('#suburb').on('change', function (e) {
        //     var id = $(this).val();
        //     if(id){
        //         getState(id);
        //     }
        //
        // });
        function getState(id){
            var url = "{{ url('beneficiary/getState') }}"+'/'+id;
            $.ajax({
                type: 'GET',
                url: url,
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
    </script>
@endsection
