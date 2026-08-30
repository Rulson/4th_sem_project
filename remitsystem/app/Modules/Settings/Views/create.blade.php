@extends('layouts.main')
@section('title', 'Settings')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">Settings</li>
@stop
@section('content')

        @include('flash::message')
        <div class="animated fadeIn">
            <div class="row">

                <form method="post" enctype="multipart/form-data" action="{{route('settings.store')}}">
                    {{csrf_field()}}
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#company-details" data-toggle="tab">Company Details</a></li>
                            <li><a href="#account-details" data-toggle="tab">Account Details</a></li>
                        </ul>
                        <div class="tab-content" style="padding: 15px">
                            <div class="tab-pane active" id="company-details">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="company_name" class="form-control" id="companyName"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>ABN</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="abn" class="form-control" id="abn"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Logo</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="file" name="logo" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Phone Number</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="phone_number" class="form-control" id="phoneNUmber"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Email Address</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="email_address" class="form-control" id="emailAddress"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Street</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="street" class="form-control" id="street"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Suburb</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="suburb" class="form-control" id="suburb"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Post Code</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="postcode" class="form-control" id="postcode"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>State</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="state" class="form-control" id="state"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Country</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="country" class="form-control" id="country"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="tab-pane" id="account-details">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Account Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="account_name" class="form-control" id="accountName"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Account No</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="account_no" class="form-control" id="accountNo"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label>BSB</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="bsb" class="form-control" id="bsb"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Bank Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="bank_name" class="form-control" id="bankName"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Description</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="description" class="form-control" id="description"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Service Charge</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" name="service_charge" class="form-control" id="serviceCharge"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Save</button>

                </form>
            </div>
        <!-- /.box-header -->

            <!-- /.box-body -->

        </div>

@endsection
