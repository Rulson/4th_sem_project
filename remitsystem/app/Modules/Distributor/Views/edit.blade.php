@extends('layouts.main')
@section('title', 'Edit Distributor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('distributors.index')}}"> Distributors </a></li>
    <li class="breadcrumb-item active">Edit</li>

@stop
@section('content')
            <form method="post" action="{{route('distributor.update',$data->id)}}">
            {{ csrf_field() }}
                <div class="animated fadeIn">
                    <div class="col-sm-12">

                        <div class="card">
                            <div class="card-header">
                                <strong>Edit Distributor</strong>
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
                                            <div class="form-group">
                                                <label>Company Name (*)</label>
                                                <input type="text" name="company_name" class="form-control" id="companyName"
                                                       placeholder="Enter Company Name" value="{{$data->companyName}}">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email (*)</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                       placeholder="Enter Email" value="{{$data->email}}">
                                            </div>

                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone Number (*)</label>
                                                <input type="text" name="phone_number" class="form-control"
                                                       id="phoneNumber" value="{{$data->phoneNumber}}"
                                                       placeholder="Enter Phone Number">
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
                                            <div class="form-group">
                                                <label>Suburb (*)</label>
                                                <input type="text" name="street" value="{{$data->street}}" class="form-control" id="street"
                                                       placeholder="Enter Street">

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>District(*)</label>
                                                <input type="text" name="suburb" class="form-control" id="suburb" value="{{$data->suburb}}"
                                                       placeholder="Enter District / Suburb">
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State / Province (*)</label>
                                                {!!Form::text('state', $data->state, array('class' => 'form-control', 'id'=>'state', 'placeholder'=>'Enter State'))!!}
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Post Code(*)</label>
                                                <input type="text" class="form-control" name="post_code" id="postcode" value="{{$data->postCode}}"
                                                       placeholder="Enter Post Code">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country (*)</label>
                                                <select name="country"
                                                        class="form-control select2-state select2-hidden-accessible"
                                                        style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    @foreach(getCountryList() as $key=>$value)
                                                        <option value="{{$key}}" {{$data->countryId == $key ? 'selected' : ''}}>  {{$value}}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Bank Details</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Name</label>
                                           @if(isset($data->accountName))
                                                <input type="text" name="account_name" class="form-control" id="accountName"
                                                       placeholder="Enter Account Name" value="{{$data->accountName}}">
                                               @else
                                                    <input type="text" name="account_name" class="form-control" id="accountName"
                                                           placeholder="Enter Account Name">

                                                @endif
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Number</label>
                                                @if(isset($data->accountNo))
                                                <input type="text" class="form-control" name="account_no" id="accountNo"
                                                       placeholder="Enter Account Number" value="{{$data->accountNo}}">
                                            @else
                                                    <input type="text" class="form-control" name="account_no" id="accountNo"
                                                           placeholder="Enter Account Number">

                                                @endif</div>

                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>BSB / Branch</label>
                                                @if(isset($data->bsb))
                                                <input type="text" name="bsb" class="form-control"
                                                       id="bsb" value="{{$data->bsb}}"
                                                       placeholder="Enter BSB / Branch">
                                                    @else
                                                    <input type="text" name="bsb" class="form-control"
                                                           id="bsb"
                                                           placeholder="Enter BSB / Branch">

                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                @if(isset($data->bankName))
                                                    {!!Form::select('bank_name', BankList(), $data->bankName, array('class' => 'form-control', 'id'=>'bank_name','placeholder'=>'Please Select'))!!}
                                                @else
                                                    {!!Form::select('bank_name', BankList(), null, array('class' => 'form-control', 'id'=>'bank_name','placeholder'=>'Please Select'))!!}
                                                @endif
                                            </div>

                                        </div>


                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>


                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Save</button>
                </div>
                        </div>
                    </div>
                </div>
            </form>



@endsection
@section('page-script')
    <script>
        $('.select2-state').select2({
            theme:"bootstrap"
        });

    </script>
    @endsection