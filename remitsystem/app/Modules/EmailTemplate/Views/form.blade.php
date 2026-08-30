<style>
    .p-t-5{
       padding-top: 5px;
    }
</style>
<div class="row">
    <div class="col-md-8">
        <div class="form-row mb-3">
            {!!Form::label('application_id', 'Application :', array('class' => 'control-label col-sm-2')) !!}
            <div class="col-sm-10">
                {{ Form::select('application_id',$application_list,  null, ['id' => 'application_id','class'=>'form-control']) }}
                @if($errors->has('application_id'))
                    {!! $errors->first('application_id', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif
            </div>
        </div>
        <div class="form-row mb-3">
            {!!Form::label('type', 'Type :', array('class' => 'control-label col-sm-2')) !!}
            <div class="col-sm-10">
                {{ Form::select('type',config('constants.email_template_type'),  null, ['id' => 'type','class'=>'form-control']) }}
                @if($errors->has('type'))
                    {!! $errors->first('type', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif
            </div>
        </div>
        <div class="form-row mb-3">
            <label for="cc" class="col-sm-2 col-form-label">Subject:</label>
            <div class="col-sm-10">
                {{ Form::text('subject',null,['id' => 'subject','class'=>'form-control']) }}

                @if($errors->has('subject'))
                    {!! $errors->first('subject', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif
            </div>

        </div>
        <div class="form-row mb-3">
            <label for="cc" class="col-sm-2 col-form-label">Active:</label>
            <div class="col-sm-10 p-t-5">
                {{ Form::checkbox('active',1,null, ['id' => 'subject','class'=>'']) }}

                @if($errors->has('active'))
                    {!! $errors->first('active', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif
            </div>

        </div>

        <div class="form-row mb-3">
            <label for="cc" class="col-sm-2 col-form-label">Message:</label>
            <div class="col-sm-10">
                {{ Form::textarea('message',null,['id' => 'editor1','class'=>'form-control']) }}
                @if($errors->has('message'))
                    {!! $errors->first('message', '<label class="control-label text-danger"
                                                           for="inputError">:message</label>') !!}
                @endif
            </div>
        </div>
        <div class="form-row mb-3 pull-right">
            <div class="col-sm-12">
            <button type="submit" class="btn btn-success mb-3">@if(isset($template))Update @else Save @endif</button>
            </div>
        </div>
    </div>
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <ul style=" padding:0 20px;
                margin:0px;">
                <li class="p-t-5"> <b><u>For All Emails :</u></b>
                    <ul style=" list-style-type:square;
    padding:0 20px;
    margin:0px;">
                        <li>
                            [APPLICATION_NAME] for Application Name
                        </li>
                        <li>
                            [APPLICATION_ADDRESS] for Application Address
                        </li>
                        <li>
                            [APPLICATION_CONTACT_EMAIL] for Application Contact Email
                        </li>
                        <li>
                            [APPLICATION_CONTACT_NUMBER] for Application Contact Number
                        </li>
                        <li>
                            [COMPANY_NAME] for Company Name
                        </li>
                        <li>[LOGIN_URL] for login page link</li>

                    </ul>
                <li class="p-t-5"> <b><u>For Verification Email :</u></b>
                    <ul style=" list-style-type:square;
    padding:0 20px;
    margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[VERIFICATION_CODE] for sender verification code</li>
                    </ul>
                </li>
                <li class="p-t-5"> <b><u>For Approve Sender Email :</u></b>
                    <ul style=" list-style-type:square;padding:0 20px; margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[SENDER_NAME] for sender full name</li>
                    </ul>
                </li>
                <li class="p-t-5"> <b><u>For Reset Password Email :</u></b>
                    <ul style=" list-style-type:square;padding:0 20px; margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[RESET_BTN] for reset password button</li>
                        <li>[RESET_URL] for reset password link</li>
                        <li>[CODE] for reset password code for app</li>
                    </ul>
                </li>
                <li class="p-t-5"> <b><u>For Create Sender User Email :</u></b>
                    <ul style=" list-style-type:square;padding:0 20px; margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[SENDER_NAME] for sender full name</li>
                        <li>[PASSWORD_UPDATE_LINK] for update password link</li>
                    </ul>
                </li>
                <li class="p-t-5"> <b><u>For Transaction Delivered Email / Transaction In Review Email :</u></b>
                    <ul style=" list-style-type:square;padding:0 20px; margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[SENDER_NAME] for sender full name</li>
                        <li>[TRANSACTION_ID] for transaction id</li>
                    </ul>
                </li>
                <li class="p-t-5"> <b><u>For SendMoney Email :</u></b>
                    <ul style=" list-style-type:square;padding:0 20px; margin:0px;">
                        <li>[FULL_NAME] for user full name</li>
                        <li>[SENDER_NAME] for sender full name</li>
                        <li>[TRANSACTION_ID] for transaction id</li>
                        <li>[BENEFICIARY_DETAIL] for beneficiary detail of sender</li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>
</div>
</div>

