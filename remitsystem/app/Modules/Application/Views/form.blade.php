<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <strong>General Information</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('name', 'Name (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'placeholder'=>'Enter Name'))!!}

                    </div>
                    @if($errors->has('name'))
                        {!! $errors->first('name', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('email', 'Email Sender (*)', array('class' => 'control-label')) !!}
                        {!!Form::email('email', null, array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Enter Email'))!!}

                    </div>
                    @if($errors->has('email'))
                        {!! $errors->first('email', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('service_charge', 'Service charge', array('class' => 'control-label')) !!}
                        {!!Form::number('service_charge', null, array('class' => 'form-control', 'id'=>'service_charge', 'placeholder'=>'Enter Service Charge'))!!}

                    </div>
                    @if($errors->has('service_charge'))
                        {!! $errors->first('service_charge', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('discount_percent', 'Referral Discount %', array('class' => 'control-label')) !!}
                        {!!Form::number('discount_percent', null, array('class' => 'form-control', 'id'=>'discount_percent', 'placeholder'=>'Enter Referral Discount Percent'))!!}

                    </div>

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!!Form::label('description', 'Description (*)', array('class' => 'control-label')) !!}
                        {!!Form::textarea('description', null, array('class' => 'form-control ckeditor', 'id'=>'description', 'placeholder'=>'Enter description'))!!}

                    </div>
                    @if($errors->has('description'))
                        {!! $errors->first('description', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Account Information</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('bank_account_name', 'Bank Account Name (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('bank_account_name', null, array('class' => 'form-control', 'id'=>'bank_account_name', 'placeholder'=>'Enter Bank Account Name'))!!}

                    </div>
                    @if($errors->has('bank_account_name'))
                        {!! $errors->first('bank_account_name', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('bank_bsb', 'Bank BSB (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('bank_bsb', null, array('class' => 'form-control', 'id'=>'bank_bsb', 'placeholder'=>'Enter Bank BSB'))!!}

                    </div>
                    @if($errors->has('bank_bsb'))
                        {!! $errors->first('bank_bsb', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('bank_account_number', 'Bank Account Number (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('bank_account_number', null, array('class' => 'form-control', 'id'=>'bank_account_number', 'placeholder'=>'Enter Bank Account Number'))!!}

                    </div>
                    @if($errors->has('bank_account_number'))
                        {!! $errors->first('bank_account_number', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('bank_name', 'Bank Name (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('bank_name', null, array('class' => 'form-control', 'id'=>'bank_name', 'placeholder'=>'Enter Bank Name'))!!}

                    </div>
                    @if($errors->has('bank_name'))
                        {!! $errors->first('bank_name', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('pay_id', "PayID", array('class' => 'control-label')) !!}
                        {!!Form::text('pay_id', null, array('class' => 'form-control', 'id'=>'pid', 'placeholder'=>'Enter PayID email'))!!}

                    </div>
                    @if($errors->has('pay_id'))
                        {!! $errors->first('pay_id', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!!Form::label('bank_notes', 'Bank Note (*)', array('class' => 'control-label')) !!}
                        {!!Form::textarea('bank_notes', null, array('class' => 'form-control ckeditor', 'id'=>'bank_notes','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('bank_notes'))
                        {!! $errors->first('bank_notes', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
            </div>

                {{--<h5> Payment Types (*)</h5>--}}
            <div class="row">
                    <div class="col-md-4">
                        <label>Payment Type</label>
                        <input  class="w-100" type="text"  name="type[]" value="Local Remit" readonly>
                    </div>
                <div class="col-md-4">
                    <label>Threshold Min (NPR)</label>
                    <input class="w-100"  type="number" step="0.01" name="min[]" placeholder="Threshold Miniumum" value="{{isset($data->payment_info['local_remit']) ? $data->payment_info['local_remit']['min'] : 0}}">
                </div>
                    <div class="col-md-4">
                        <label>Threshold Max (NPR)</label>
<input class="w-100"  type="number" step="0.01" name="max[]" placeholder="Threshold Maximum" value="{{isset($data->payment_info['local_remit']) ? $data->payment_info['local_remit']['max'] : 0}}">
                    </div>


            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <label>Payment Type</label>
                    <input class="w-100"  type="text"  name="type[]" value="Bank Transfer" readonly>
                    </div>
                <div class="col-md-4">
                    <label>Threshold Min (NPR)</label>
                    <input class="w-100"  type="number" step="0.01" name="min[]" placeholder="Threshold Miniumum" value="{{isset($data->payment_info['bank_transfer']) ? $data->payment_info['bank_transfer']['min'] : 0}}">
                </div>
                    <div class="col-md-4">
                        <label>Threshold Max (NPR)</label>
                        <input class="w-100"  type="number" step="0.01" name="max[]" placeholder="Threshold Maximum" value="{{isset($data->payment_info['bank_transfer']) ? $data->payment_info['bank_transfer']['max'] : 0}}">
                    </div>

            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Contact Information</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('contact_email', 'Contact Email (*)', array('class' => 'control-label')) !!}
                        {!!Form::email('contact_email', null, array('class' => 'form-control', 'id'=>'contact_email', 'placeholder'=>'Enter Email'))!!}

                    </div>
                    @if($errors->has('contact_email'))
                        {!! $errors->first('contact_email', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('phone_number', 'Contact Number (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('phone_number', null, array('class' => 'form-control', 'id'=>'phone_number', 'placeholder'=>'Enter Phone Number'))!!}

                    </div>
                    @if($errors->has('phone_number'))
                        {!! $errors->first('phone_number', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('facebook_info', 'Facebook Username', array('class' => 'control-label')) !!}
                        {!!Form::text('facebook_info', null, array('class' => 'form-control', 'id'=>'facebook_info', 'placeholder'=>'Enter Facebook Username'))!!}

                    </div>
                    @if($errors->has('facebook_info'))
                        {!! $errors->first('facebook_info', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('viber_info', 'Viber Number', array('class' => 'control-label')) !!}
                        {!!Form::text('viber_info', null, array('class' => 'form-control', 'id'=>'viber_info', 'placeholder'=>'Enter Viber Number'))!!}

                    </div>
                    @if($errors->has('viber_info'))
                        {!! $errors->first('viber_info', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('whatsapp_info', 'Whatsapp Number', array('class' => 'control-label')) !!}
                        {!!Form::text('whatsapp_info', null, array('class' => 'form-control', 'id'=>'whatsapp_info', 'placeholder'=>'Enter Whatsapp Number'))!!}

                    </div>
                    @if($errors->has('whatsapp_info'))
                        {!! $errors->first('whatsapp_info', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('call_info', 'Call Number', array('class' => 'control-label')) !!}
                        {!!Form::text('call_info', null, array('class' => 'form-control', 'id'=>'call_info', 'placeholder'=>'Enter Phone Number'))!!}

                    </div>
                    @if($errors->has('whatsapp_info'))
                        {!! $errors->first('whatsapp_info', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('contact_person', 'Contact Person', array('class' => 'control-label')) !!}
                        {!!Form::text('contact_person', null, array('class' => 'form-control', 'id'=>'contact_person', 'placeholder'=>'Contact Person'))!!}

                    </div>
                    @if($errors->has('contact_person'))
                        {!! $errors->first('contact_person', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('designation', 'Designation', array('class' => 'control-label')) !!}
                        {!!Form::text('designation', null, array('class' => 'form-control', 'id'=>'designation', 'placeholder'=>'Designation'))!!}

                    </div>
                    @if($errors->has('designation'))
                        {!! $errors->first('designation', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('company_name', 'Company Name', array('class' => 'control-label')) !!}
                        {!!Form::text('company_name', null, array('class' => 'form-control', 'id'=>'company_name', 'placeholder'=>'Company Name'))!!}

                    </div>
                    @if($errors->has('company_name'))
                        {!! $errors->first('company_name', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

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
                        <label class="control-label">Street (*)</label>
                        {!!Form::text('street', null, array('class' => 'form-control', 'id'=>'street','placeholder'=>'Enter Street'))!!}
                        @if($errors->has('street'))
                            {!! $errors->first('street', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if($errors->has('suburb')) {{'has-error'}} @endif">
                        <label class="control-label">Suburb (*)</label>
                        {!!Form::text('suburb', null, array('class' => 'form-control', 'id'=>'suburb','placeholder'=>'Enter Suburb'))!!}
                        @if($errors->has('suburb'))
                            {!! $errors->first('suburb', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if($errors->has('state')) {{'has-error'}} @endif">
                        <label class="control-label">State (*)</label>
                        {!!Form::select('state', australiaStateLists(), null, array('class' => 'form-control','placeholder'=>'Choose State', 'id'=>'state'))!!}
                        @if($errors->has('state'))
                            {!! $errors->first('state', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif">
                        <label class="control-label">Post Code (*)</label>
                        {!!Form::text('postcode', null, array('class' => 'form-control', 'id'=>'postcode', 'placeholder'=>'Enter Postal Code'))!!}
                        @if($errors->has('postcode'))
                            {!! $errors->first('postcode', '<label class="control-label text-danger"
                                                                    for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">

                        <label class="control-label">Country (*)</label>
                        {!!Form::select('country_id', $countries,isset($data)?$data->country_id : 13, array('class' => 'form-control', 'id'=>'country_id'))!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Other Information</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('agent_id', 'Agent (*)', array('class' => 'control-label')) !!}
                        {{ Form::select('agent_id', agentUserList(),null, ['id' => 'agent_id','class'=>'form-control','placeholder'=>'Choose Agent']) }}
                    </div>
                    @if($errors->has('agent_id'))
                        {!! $errors->first('agent_id', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('package_name', 'Package Name (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('package_name', null, array('class' => 'form-control', 'id'=>'package_name', 'placeholder'=>'Enter Package Name'))!!}

                    </div>
                    @if($errors->has('package_name'))
                        {!! $errors->first('package_name', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('firebase_key', 'Firebase Key', array('class' => 'control-label')) !!}
                        {!!Form::text('firebase_key', null, array('class' => 'form-control', 'id'=>'firebase_key', 'placeholder'=>'Enter Package Name'))!!}

                    </div>
                    @if($errors->has('firebase_key'))
                        {!! $errors->first('firebase_key', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('domain_url', 'Domain Url (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('domain_url', null, array('class' => 'form-control', 'id'=>'domain_url', 'placeholder'=>'Enter Domain Url'))!!}

                    </div>
                    @if($errors->has('domain_url'))
                        {!! $errors->first('domain_url', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('appstore_url', 'Appstore Url', array('class' => 'control-label')) !!}
                        {!!Form::text('appstore_url', null, array('class' => 'form-control', 'id'=>'appstore_url', 'placeholder'=>'Enter Appstore Url'))!!}

                    </div>
                    @if($errors->has('appstore_url'))
                        {!! $errors->first('appstore_url', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('playstore_url', 'Playstore Url', array('class' => 'control-label')) !!}
                        {!!Form::text('playstore_url', null, array('class' => 'form-control', 'id'=>'playstore_url', 'placeholder'=>'Enter Playstore Url'))!!}

                    </div>
                    @if($errors->has('playstore_url'))
                        {!! $errors->first('playstore_url', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('alert', 'Alert', array('class' => 'control-label')) !!}
                        {!!Form::text('alert', null, array('class' => 'form-control', 'id'=>'alert', 'placeholder'=>'Enter Playstore Url'))!!}

                    </div>
                    @if($errors->has('alert'))
                        {!! $errors->first('alert', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('alert_link', 'Alert Link', array('class' => 'control-label')) !!}
                        {!!Form::text('alert_link', null, array('class' => 'form-control', 'id'=>'alert_link', 'placeholder'=>'Enter Playstore Url'))!!}

                    </div>
                    @if($errors->has('alert_link'))
                        {!! $errors->first('alert_link', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('published', 'Published',["class" => "radiostatus"]) !!}
                        <label class="radio-inline"> {!! Form::radio('published', 1, true) !!}Yes </label>
                        <label class="radio-inline"> {!! Form::radio('published', 0, false) !!}No </label>
                    </div>
                </div>
                @if(isset($data) && $data->logo)
                    <div class="col-md-12">
                        <img src="{{ url('/application/'.$data->logo) }}" height="50px" width="50px">
                    </div>
                @endif
                <br>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('image')) {{'has-error'}} @endif">
                        {!! Form::label('application_logo', 'Logo',["class" => "file"]) !!}
                        {!!Form::file('application_logo', null, array('class' => 'form-control', 'id'=>'application_logo',))!!}
                    </div>
                    @if($errors->has('application_logo'))
                        {!! $errors->first('application_logo', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('terms_and_conditions', 'Terms and Conditions Url', array('class' => 'control-label')) !!}
                        {!!Form::text('terms_and_conditions', null, array('class' => 'form-control', 'id'=>'terms_and_conditions', 'placeholder'=>'Enter terms and conditions url'))!!}

                    </div>
                    @if($errors->has('terms_and_conditions'))
                        {!! $errors->first('terms_and_conditions', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Notice Information</strong>
        </div>
        <div class="card-body">
            <h5>Dashboard Notice</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('dashboard_notice_from', 'From (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('dashboard_notice_from', isset($data->notices['dashboard']) ? $data->notices['dashboard']['from']:null, array('class' => 'form-control', 'id'=>'dashboard_notice_from', 'placeholder'=>'DD/MM/YYYY','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('dashboard_notice_from'))
                        {!! $errors->first('dashboard_notice_from', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('dashboard_notice_to', 'To (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('dashboard_notice_to',  isset($data->notices['dashboard']) ? $data->notices['dashboard']['to']:null, array('class' => 'form-control', 'id'=>'dashboard_notice_to', 'placeholder'=>'DD/MM/YYYY','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('dashboard_notice_to'))
                        {!! $errors->first('dashboard_notice_to', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('dashboard_notice_url', 'Image Click Link', array('class' => 'control-label')) !!}
                        {!!Form::text('dashboard_notice_url', isset($data->notices['dashboard']['url']) ? $data->notices['dashboard']['url']:null, array('class' => 'form-control', 'id'=>'dashboard_notice_url', 'placeholder'=>'Enter Url','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('dashboard_notice_url'))
                        {!! $errors->first('dashboard_notice_url', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                @if(isset($data) && isset($data->notices['dashboard']['image']))
                    <div class="col-md-12">
                        <img src="{{ $data->notices['dashboard']['image'] }}" height="50px" width="50px">
                    </div>
                @endif
                <br>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('dashboard_notice_image')) {{'has-error'}} @endif">
                        {!! Form::label('dashboard_notice_image', 'Image',["class" => "file control-label"]) !!}
                        {!!Form::file('dashboard_notice_image', null, array('class' => 'form-control', 'id'=>'dashboard_notice_image'))!!}
                    </div>
                    @if($errors->has('dashboard_notice_image'))
                        {!! $errors->first('dashboard_notice_image', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif
                    <div id="file-size">Please upload image with 16:9 Aspect Ratio.</div>

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!!Form::label('dashboard_notice', 'Notice (*)', array('class' => 'control-label')) !!}
                        {!!Form::textarea('dashboard_notice', isset($data->notices['dashboard']) ? $data->notices['dashboard']['notice']:null, array('class' => 'form-control ckeditor', 'id'=>'dashboard_notice', 'placeholder'=>'Enter Notice'))!!}

                    </div>
                    @if($errors->has('dashboard_notice'))
                        {!! $errors->first('dashboard_notice', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
            </div>

            <h5>Sendmoney Notice</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('sendmoney_disable', 'Disable Sendmoney',["class" => "radiostatus"]) !!}
                        @if(isset($data))
                        <label class="radio-inline"><input type="radio" name="sendmoney_disable" value="true" {{(isset($data->notices['sendmoney']['sendmoney_disable']) && ($data->notices['sendmoney']['sendmoney_disable'] == true)) ? 'checked' : ''}}> Yes </label>
                        <label class="radio-inline">  <input type="radio" name="sendmoney_disable" value="false" {{(isset($data->notices['sendmoney']['sendmoney_disable']) && ($data->notices['sendmoney']['sendmoney_disable'] == false)) ? 'checked' : ''}}> No </label>
                        @else
                            <label class="radio-inline"><input type="radio" name="sendmoney_disable" value="true" > Yes </label>
                            <label class="radio-inline">  <input type="radio" name="sendmoney_disable" value="false" checked> No </label>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('sendmoney_notice_from', 'From (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('sendmoney_notice_from', isset($data->notices['sendmoney']) ? $data->notices['sendmoney']['from']:null, array('class' => 'form-control', 'id'=>'sendmoney_notice_from', 'placeholder'=>'DD/MM/YYYY','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('sendmoney_notice_from'))
                        {!! $errors->first('sendmoney_notice_from', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('sendmoney_notice_to', 'To (*)', array('class' => 'control-label')) !!}
                        {!!Form::text('sendmoney_notice_to', isset($data->notices['sendmoney']) ? $data->notices['sendmoney']['to']:null, array('class' => 'form-control', 'id'=>'sendmoney_notice_to', 'placeholder'=>'DD/MM/YYYY','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('sendmoney_notice_to'))
                        {!! $errors->first('sendmoney_notice_to', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!!Form::label('sendmoney_notice_url', 'Image Click Link', array('class' => 'control-label')) !!}
                        {!!Form::text('sendmoney_notice_url', isset($data->notices['sendmoney']['url']) ? $data->notices['sendmoney']['url']:null, array('class' => 'form-control', 'id'=>'sendmoney_notice_url', 'placeholder'=>'Enter Url','autocomplete'=>'off'))!!}

                    </div>
                    @if($errors->has('sendmoney_notice_url'))
                        {!! $errors->first('sendmoney_notice_url', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                @if(isset($data) && isset($data->notices['sendmoney']['image']))
                    <div class="col-md-12">
                        <img src="{{ $data->notices['sendmoney']['image'] }}" height="50px" width="50px">
                    </div>
                @endif
                <br>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('sendmoney_notice_image')) {{'has-error'}} @endif">
                        {!! Form::label('sendmoney_notice_image', 'Image',["class" => "file control-label"]) !!}
                        {!!Form::file('sendmoney_notice_image', null, array('class' => 'form-control', 'id'=>'sendmoney_notice_image'))!!}
                    </div>
                    @if($errors->has('sendmoney_notice_image'))
                        {!! $errors->first('sendmoney_notice_image', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!!Form::label('sendmoney_notice', 'Notice (*)', array('class' => 'control-label')) !!}
                        {!!Form::textarea('sendmoney_notice', isset($data->notices['sendmoney']) ? $data->notices['sendmoney']['notice']:null, array('class' => 'form-control ckeditor', 'id'=>'sendmoney_notice', 'placeholder'=>'Enter Notice'))!!}

                    </div>
                    @if($errors->has('sendmoney_notice'))
                        {!! $errors->first('sendmoney_notice', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                    @endif

                </div>
            </div>


        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#agent_id').select2({
           theme: "bootstrap"
        });
    });
    $(document).ready(function () {
    $('input[name="dashboard_notice_from"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
        }
    });
    $('input[name="dashboard_notice_from"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('input[name="dashboard_notice_from"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    $('input[name="dashboard_notice_to"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    });
    $('input[name="dashboard_notice_to"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('input[name="dashboard_notice_to"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    $('input[name="sendmoney_notice_from"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    });
    $('input[name="sendmoney_notice_from"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('input[name="sendmoney_notice_from"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    $('input[name="sendmoney_notice_to"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    });
    $('input[name="sendmoney_notice_to"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('input[name="sendmoney_notice_to"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    });
</script>
