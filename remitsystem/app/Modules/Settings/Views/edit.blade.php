@extends('layouts.main')
@section('title', 'Settings')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">Settings</li>
@stop
@section('content')

    @include('flash::message')
    <div class="animated fadeIn">
        <form method="post" enctype="multipart/form-data" action="{{route('settings.update',$data->id)}}">
            {{csrf_field()}}
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#company_details" role="tab"><i class="icon-list"></i> Company Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#account_details" role="tab"><i class="icon-speech"></i> Account Details</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="company_details" role="tabpanel">
                <div class="row">
                    <div class="col-md-2">
                        <label>Name</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" name="company_name"  value="{{ $data->company_name }}" class="form-control" id="companyName"
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
                                   value="{{$data->abn}}" placeholder="">
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
                            <input type="text" name="phone_number" value="{{$data->phone_number}}" class="form-control" id="phoneNUmber"
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
                            <input type="text" name="email_address" value="{{$data->email_address}}" class="form-control" id="emailAddress"
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
                            <input type="text" name="street" value="{{$data->street}}" class="form-control" id="street"
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
                            <input type="text" name="suburb" class="form-control" value="{{$data->suburb}}" id="suburb"
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
                            <input type="text" name="postcode" value="{{$data->postcode}}" class="form-control" id="postcode"
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
                            <input type="text" name="state" value="{{$data->state}}" class="form-control" id="state"
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
                            <input type="text" name="country" value="{{$data->country}}" class="form-control" id="country"
                                   placeholder="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-3" id="account_details" role="tabpanel">
                <div class="row">
                    <div class="col-md-2">
                        <label>Account Name</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" name="account_name"  class="form-control" id="accountName" value="{{$data->account_name}}"
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
                            <input type="text" name="account_no"  class="form-control" id="accountName" value="{{$data->account_no}}"
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
                            <input type="text" name="bsb" value="{{$data->bsb}}" class="form-control" id="bsb"
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
                            <input type="text" name="bank_name" value="{{$data->bank_name}}" class="form-control" id="bankName"
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
                            <input type="text" name="description" value="{{$data->description}}" class="form-control" id="description"
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
                            <input type="text" name="service_charge" value="{{$data->service_charge}}" class="form-control" id="serviceCharge"
                                   placeholder="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Save</button>

        </form>
    </div>

@endsection



