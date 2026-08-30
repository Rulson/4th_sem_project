@extends('layouts.main')
@section('title', 'Senders')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Senders</li>
    <li class="breadcrumb-item active">{{getSenderName($sender->sender_id)}}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="send-email-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>

    <div class="modal fade sendingmodal" id="money-transfer-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    <div class="modal" id="SenderPopUpImage1" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{--<img src="" id="imgSrc" width="100%">--}}
                    <div id="senderIds" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block" width="100%" src="" alt="First Image" id="sender-first-image">
                            </div>
                            <div class="carousel-item item-2">
                                <img class="d-block" width="100%" src="" alt="Second Image" id="sender-second-image">
                            </div>
                        </div>
                        <a class="carousel-control-prev next-controls" href="#senderIds" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next next-controls" href="#senderIds" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <span id="noSenderImage"></span>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="identificationModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Identification</h3>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post"
                      action="{{route('identification.update', [$sender->sender_id])}}"
                      id="form_identification_update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Identification Details</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif">
                                                    {!!Form::label('id_type', 'Id Type (*)', array('class' => 'control-label')) !!}
                                                    {!!Form::select('id_type', identificationTypes(),$sender->id_type, array('class' => 'form-control', 'id'=>'id_type','placeholder'=>'Id Type','required'))!!}
                                                    @if($errors->has('id_type'))
                                                        {!! $errors->first('id_type', '<label class="control-label"
                                                                                               for="inputError">:message</label>') !!}
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif">
                                                    {!!Form::label('issued_by', 'Issued By (*)', array('class' => 'control-label')) !!}
                                                    <select name="issued_by" class="form-control" id="issued_by">
                                                        <option></option>
                                                    </select>
                                                    {{--  {!!Form::text('issued_by', $sender->issued_by, array('class' => 'form-control', 'id'=>'issued_by', 'placeholder'=>'Enter Issued By'))!!}--}}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('id_number')) {{'has-error'}} @endif">
                                                    {!!Form::label('id_number', 'Id Number (*)', array('class' => 'control-label')) !!}
                                                    {!!Form::text('id_number', $sender->id_number, array('class' => 'form-control', 'id'=>'id_number', 'placeholder'=>'Enter Postal Code'))!!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('expiry_date')) {{'has-error'}} @endif">
                                                    {!!Form::label('expiry_date', 'Expiry Date (*)', array('class' => 'control-label')) !!}
                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>

                                                        @if(isset($user) && $user->expiry_date != '')
                                                            {!!Form::text('expiry_date', $user->expiry_date ,array('class' => 'form-control', 'id'=>'expiry_date_identification_edit', 'placeholder'=>'Enter Expiry Date', 'autocomplete' => 'off'))!!}
                                                        @else
                                                            {!!Form::text('expiry_date',$sender->expiry_date, array('class' => 'form-control', 'id'=>'expiry_date_identification_edit', 'placeholder'=>'Enter Expiry Date', 'autocomplete' => 'off'))!!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('image')) {{'has-error'}} @endif">

                                                    {!! Form::label('image','Upload Identification (*)', ['class' => 'control-label']) !!}
                                                    {!!  Form::file('image',array('required'))  !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group @if($errors->has('image1')) {{'has-error'}} @endif">

                                                    {!! Form::label('image1','Upload Identification', ['class' => 'control-label']) !!}
                                                    {!!  Form::file('image1')  !!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <input type="hidden" name="type" value="modal">
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Change Status</h3>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post"
                      action="{{route('sender.changeStatus', [$sender->sender_id])}}"
                      id="my_form">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <select class="form-control" name="status_id">
                            @foreach($sender_status as $status)
                                <option
                                    value="{{$status->id}}"{{($sender->sender_status_id==$status->id)? 'selected':''}} >{{$status->name}}</option>
                            @endforeach

                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                    <input type="hidden" name="type" value="modal">
                </form>

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Sender Details</strong>
                            <div class="pull-right">
                                @if(in_array(Auth::user()->level_id, [1,2,3,8]))
                                    @php
                                        $user = \App\Modules\User\Models\User::leftJoin('person','person.id','users.person_id')->where('users.person_id',$sender->sender_person_id)->first();
                                    @endphp
                                    @if(isset($user) && $user->firebase_token != '')
                                        <a type="button" data-toggle="modal"
                                           data-url="{{route('sender.push-notification',$sender->sender_id)}}"
                                           data-target="#send-email-modal"
                                           class="btn btn-primary  btn-sm"><i
                                                class="fa fa-envelope">
                                                Push Notification</i></a>
                                    @endif
                                    <a type="button" data-toggle="modal"
                                       data-url="{{route('sender.send-mail',$sender->sender_id)}}"
                                       data-target="#send-email-modal"
                                       class="btn btn-primary  btn-sm"><i
                                            class="fa fa-envelope">
                                            Send Email</i></a>
                                    @php
                                        $notApprovedClients = App\Modules\User\Models\User::leftJoin('person','person.id','=','users.person_id')
                                                        ->leftJoin('senders','senders.person_id','=','users.person_id')
                                                        ->where([['users.level_id',5],['users.user_status_id','!=',2]])
                                                        ->where('users.person_id',$sender->sender_person_id)
                                                        //->where('senders.sender_status_id',1)
                                                        ->orderBy('users.id','desc')
                                                        ->select('senders.*','person.*','users.*','users.id as user_id')->first();
                                    @endphp
                                    @if($notApprovedClients)
                                        <a type="button"
                                           href="{{route('sender.approveSender',$notApprovedClients->user_id)}}"
                                           class="btn btn-success  btn-sm"><i
                                                class="fa fa-paper-plane-o"
                                                onclick="return confirm('Are you sure? You want to approve the request')">
                                                @if($notApprovedClients->user_status_id == 1)
                                                    Activate New User
                                                @else
                                                    Reactivate User
                                                @endif </i></a>
                                    @endif
                                    <a type="button" data-toggle="modal"
                                       data-url="{{route('transaction.sender.edit',$sender->sender_id)}}"
                                       data-target="#money-transfer-modal"
                                       class="btn btn-primary  btn-sm"><i
                                            class="fa fa-pencil">
                                            Edit sender</i></a>
                                @elseif(in_array(Auth::user()->level_id, [4,5]))
                                    <a type="button" data-toggle="modal" data-target="#money-transfer-modal"
                                       data-url="{{route('transaction.beneficiary.add').'?id='.$sender->sender_id}}"
                                       id="add_ben" class="btn btn-success btn-sm">Add New Beneficiary</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Sender Id</dt>
                                        <dd>{{format_id($sender->sender_id,'S')}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Sender Name</dt>
                                        <dd>{{$sender->first_name}} {{$sender->last_name}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Phone</dt>
                                        <dd>{{$sender->number}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Email</dt>
                                        <dd>{{$sender->email}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-4">
                                    @if(in_array(Auth::user()->level_id, [1,2,3,5,8]))
                                        <a type="button" data-toggle="modal"
                                           data-url="{{route('sender.add.address',$sender->sender_id)}}"
                                           title="Add new address"
                                           data-target="#money-transfer-modal"
                                           class="btn btn-primary btn-sm pull-right"><i
                                                class="fa fa-plus"></i></a>
                                    @endif
                                    <dl>
                                        <dt>Address
                                            @if(!empty($address_proof_doc))
                                                <span class="pull-right">
                                            <a class="" data-toggle="modal" data-target="#SenderPopUpImage1"
                                               data-img="{{$address_proof_doc}}">
                                                <button type="button" class="buttons">View</button>
                                            </a>
                                            <a class="mr-2"
                                               href="{{route('identification.get',['sender_id' => $sender->sender_id, 'type' => 'address_proof'])}}">
                                                <button type="button" class="buttons">Download</button>
                                            </a>
                                    </span>
                                            @else
                                                <span>(No address proof provided)</span>
                                            @endif
                                        </dt>
                                        <dd>{{$sender->street}},{{$sender->suburb}} ,{{$sender->postcode}}
                                            ,{{$sender->state}},{{$sender->country}}
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Created By</dt>
                                        <dl>{{get_user_name($sender->added_by)}}</dl>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Created At</dt>
                                        <dl>{{standard_date($sender->dateAdded)}}</dl>
                                    </dl>
                                </div>
                                @php $user = \App\Modules\User\Models\User::where('person_id',$sender->sender_person_id)->first();@endphp
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Email Verified At</dt>
                                        <dl>{{ ($user && $user->email_verified_at)? standard_date($user->email_verified_at) : ''}}</dl>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <strong>Status</strong>
                        </div>
                        <div class="card-body">
                            <dl>
                                <b>{{$sender->status}}</b>
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8 )
                                    <span class="pull-right"><button
                                            type="button" class="buttons" data-toggle="modal"
                                            data-target="#statusModal"
                                            data-whatever="{{$sender->status}}">Change Status</button></span>
                                @endif
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <strong>Identification Details</strong>
                            <div class="pull-right">

                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8 )

                                    <a type="button" href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                       data-target="#identificationModal"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(in_array(Auth::user()->level_id, [1,2,3,5,8]))
                                    <a type="button" data-toggle="modal"
                                       data-url="{{route('sender.add.new.identification',$sender->sender_id)}}"
                                       title="Add new identification"
                                       data-target="#money-transfer-modal"
                                       class="btn btn-primary btn-sm"><i
                                            class="fa fa-plus"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <dl>
                                <dt>Date of Birth</dt>
                                <dd>{{$sender->dob}}</dd>
                                <dt>Identification Type</dt>
                                <dd>{{getIdTypeName($sender->id_type)}}</dd>
                                <dt>Identification Number</dt>
                                <dd>{{$sender->id_number}}</dd>
                                <dt>Issued By</dt>
                                <dd>{{$sender->issued_by}}</dd>
                                <dt>Expiry Date</dt>
                                <dd>{{$sender->expiry_date}}</dd>

                                <dt>
                                    <span class="pull-right">
                                            <a data-toggle="modal" data-target="#SenderPopUpImage1"
                                               data-img="{{$first_doc}}" data-img1="{{$second_doc}}">
                                                <button type="button" class="buttons">View</button>
                                            </a>
                                            <a href="{{route('identification.get',[$sender->sender_id])}}">
                                                <button type="button" class="buttons">Download</button>
                                            </a>
                                    </span>
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-group mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0">{{$transaction_count}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total
                                                Transaction
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0">{{$beneficiary}}</div>

                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total
                                                Beneficiaries
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="h1 text-muted text-right mb-4">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="h4 mb-0 font-lg">AUD {{$transactionData['sumSendingAmount']}}
                                            | NPR {{$transactionData['sumPaymentAmount']}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total Amount
                                                Sent
                                            </small>
                                        </a>
                                        <div class="progress progress-xs mt-3 mb-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#transactions" role="tab"
                                               aria-controls="home"><i class="icon-calculator"></i> Transactions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#beneficiaries" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Beneficiaries</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#coupons" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Coupon Usage</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#referrals" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Referrals</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="transactions" role="tabpanel">

                                            <div class="card" style="overflow-x: scroll;">

                                                <table id="exampleTransaction"
                                                       class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Transaction ID</th>
                                                        <th>Date</th>
                                                        <th>Beneficiary Name</th>
                                                        <th>Total Amount</th>
                                                        <th>Service Charge</th>
                                                        <th>Sending Amount</th>
                                                        <th>Payment Amount</th>
                                                        <th>Client Rate</th>
                                                        <th>Added By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($transaction as $key=>$value)
                                                        <tr>
                                                            <td>{{format_id($value['transaction_id'],"T")}}</td>
                                                            <td>{{standard_date($value['date'])}}</td>
                                                            <td>{{$value['beneficiary_full_name']}}</td>
                                                            <td>{{$value['totalAmount']}}</td>
                                                            <td>{{$value['serviceCharge']}}</td>
                                                            <td>{{$value['sendingAmount']}}</td>
                                                            <td>{{$value['paymentAmount']}}</td>
                                                            <td>{{$value['exchangeRate']}}</td>
                                                            <td>{{get_user_name($value['addedBy'])}}</td>
                                                            <td>
                                                                <a href="{{route('transactions.show', [$value['transaction_id']])}}"
                                                                   data-toggle="tooltip" title="View"
                                                                   class="btn btn-sm btn-success"><i
                                                                        class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>

                                                    @endforeach


                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="beneficiaries" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">


                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Beneficiary ID</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Street</th>
                                                        <th>Suburb</th>
                                                        <th>Postcode</th>
                                                        <th>State</th>
                                                        <th>Account Name</th>
                                                        <th>Account No</th>
                                                        <th>Bank</th>
                                                        <th>Bsb</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($sender_beneficiary as $key=>$value)
                                                        <tr>
                                                            <td>{{format_id($value['beneficiary_id'],"B")}}</td>
                                                            <td>{{$value['full_name']}}</td>
                                                            <td>{{$value['number']}}</td>
                                                            <td>{{$value['street']}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->suburb}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->postcode}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->state}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->account_name}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->account_no}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->bank_name}}</td>
                                                            <td>{{getBeneficiaryDetails($value['beneficiary_id'])->bsb}}</td>
                                                            <td>
                                                                <a href="{{route('beneficiary.show', [$value['beneficiary_id']])}}"
                                                                   data-toggle="tooltip" title="View"
                                                                   class="btn btn-sm btn-success"><i
                                                                        class="fa fa-eye"></i></a>
                                                                @if(Auth::user()->level_id != 6)
                                                                    <a href="{{route('beneficiary.edit',[$value['beneficiary_id']])}}"
                                                                       title="Edit" class="btn btn-sm btn-primary"><i
                                                                            class="fa fa-edit"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="addresses" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Address ID</th>
                                                        <th>Street</th>
                                                        <th>Suburb</th>
                                                        <th>State</th>
                                                        <th>PostCode</th>
                                                        <th>Country</th>
                                                        <th>Status</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($allAddress as $address)

                                                        <tr>
                                                            <td>{{format_id($address->addressId,'A')}}</td>
                                                            <td>{{$address->street}}</td>
                                                            <td>{{$address->suburb}}</td>
                                                            <td>{{$address->state}}</td>
                                                            <td>{{$address->postcode}}</td>
                                                            <td>{{$address->country}}</td>
                                                            @if($address->current == 1)
                                                                <td>Active</td>
                                                            @else
                                                                <td>Inactive</td>
                                                            @endif
                                                        </tr>

                                                    @endforeach


                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="identifications" role="tabpanel">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Identification Id</th>
                                                    <th width="15%">Id Type</th>
                                                    <th width="20%">Issued By</th>
                                                    <th width="20%">Id Number</th>
                                                    <th>Expiry Date</th>
                                                    <th>Status</th>
                                                    @if(Auth::user()->level_id ==1 || Auth::user()->level_id == 2)
                                                        <th>Action</th>
                                                    @endif

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($allIdentifications as $identification)
                                                    <tr>
                                                        <td width="10%">{{ format_id($identification->identification_id, 'I') }}</td>
                                                        <td width="15%">{{$identification->name}}</td>
                                                        <td width="20%">{{$identification->issued_by}}</td>
                                                        <td width="20%">{{$identification->id_number}}</td>
                                                        <td>{{$identification->expiry_date}}</td>
                                                        @if($identification->Identification_status_id == 1)
                                                            <td>Pending</td>
                                                        @elseif($identification->Identification_status_id == 2)
                                                            <td>Verified</td>
                                                        @else
                                                            <td>Declined</td>
                                                        @endif
                                                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                                            @if($identification->Identification_status_id == 1)
                                                                <td>
                                                                    <form method="post"
                                                                          action="{{route('sender.identification.approve')}}">
                                                                        {{csrf_field()}}
                                                                        {{Form::hidden('identification_id',$identification->identification_id)}}
                                                                        {{Form::hidden('sender_id',$sender->sender_id)}}
                                                                        <button type="submit" class="btn-sm btn-primary"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom" title="Approve"
                                                                                onclick="return confirm('Are you sure? You want to approve the identification request ?')">
                                                                            <i
                                                                                class="fa fa-check"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                    <form method="post"
                                                                          action="{{route('sender.identification.decline')}}">
                                                                        {{csrf_field()}}
                                                                        {{Form::hidden('identification_id',$identification->identification_id)}}
                                                                        {{Form::hidden('sender_id',$sender->sender_id)}}

                                                                        <button type="submit" class="btn-sm btn-danger"
                                                                                onclick="return confirm('Are you sure? You want to decline the identification request ?')"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom" title="Decline">
                                                                            <i
                                                                                class="fa fa-trash"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            @elseif($identification->Identification_status_id == 2)
                                                                <td>
                                                                    <form method="post"
                                                                          action="{{route('sender.identification.decline')}}">
                                                                        {{csrf_field()}}
                                                                        {{Form::hidden('identification_id',$identification->identification_id)}}
                                                                        {{Form::hidden('sender_id',$sender->sender_id)}}
                                                                        <button type="submit" class="btn-sm btn-danger"
                                                                                onclick="return confirm('Are you sure? You want to decline the identification request ?')"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom" title="Decline">
                                                                            <i
                                                                                class="fa fa-trash"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <form method="post"
                                                                          action="{{route('sender.identification.approve')}}">
                                                                        {{csrf_field()}}
                                                                        {{Form::hidden('identification_id',$identification->identification_id)}}
                                                                        {{Form::hidden('sender_id',$sender->sender_id)}}
                                                                        <button type="submit" class="btn-sm btn-primary"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom" title="Approve"
                                                                                onclick="return confirm('Are you sure? You want to approve the identification request ?')">
                                                                            <i
                                                                                class="fa fa-check"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    </tr>

                                                @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                        <div class="tab-pane" id="coupons" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th width="10%">Date</th>
                                                        <th width="10%">Coupon</th>
                                                        <th width="10%">Code</th>
                                                        <th width="10%">Discount</th>
                                                        <th width="10%">Transaction Id</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($allCoupons as $coupon)

                                                        <tr>
                                                            <td>{{format_date($coupon->created_at)}}</td>
                                                            <td>{{$coupon->coupon->name}}</td>
                                                            <td>{{$coupon->coupon->code}}</td>
                                                            <td>{{$coupon->coupon->discount_value}} {{$coupon->coupon->discount_unit == 'p' ? '%':'$'}}</td>
                                                            <td>
                                                                <a href="{{route('transactions.show',$coupon->transaction_id)}}">{{$coupon->transaction_id}}</a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4">No record</td>
                                                        </tr>

                                                    @endforelse


                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="referrals" role="tabpanel">
                                            <div class="row">
                                                <div class="col-sm-12 col-lg-4">
                                                    <div class="card" style="    width: 225px;">
                                                        <div class="card-body p-0 clearfix">
                                                            <i class="fa fa-money bg-primary p-4 px-5 font-2xl mr-3 float-left"></i>
                                                            <div class="h6 text-primary mb-0 pt-3">
                                                                {{!empty($referral_points->total_claimed) ? $referral_points->total_claimed : 0}}</div>
                                                            <div
                                                                class="text-muted text-uppercase font-weight-bold font-xs">
                                                                Points Claimed
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/.col-->
                                                <div class="col-sm-12 col-lg-4">
                                                    <div class="card">
                                                        <div class="card-body p-0 clearfix">
                                                            <i class="fa fa-money bg-red p-4 px-5 font-2xl mr-3 float-left"></i>
                                                            <div class="h6 text-info mb-0 pt-3">
                                                                {{!empty($referral_points->total_used) ? $referral_points->total_used : 0}}</div>
                                                            <div
                                                                class="text-muted text-uppercase font-weight-bold font-xs">
                                                                Points Used
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/.col-->
                                                <div class="col-sm-12 col-lg-4">
                                                    <div class="card">
                                                        <div class="card-body p-0 clearfix">
                                                            <i class="fa fa-money bg-success p-4 px-5 font-2xl mr-3 float-left"></i>
                                                            <div class="h6 text-warning mb-0 pt-3">
                                                                {{!empty($referral_points->remaining) ? $referral_points->remaining : 0}}</div>
                                                            <div
                                                                class="text-muted text-uppercase font-weight-bold font-xs">
                                                                Points Remaining
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Date</th>
                                                    <th width="10%">Transaction</th>
                                                    <th width="40%">Description</th>
                                                    <th width="10%">Points</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($allReferrals as $referral)
                                                    <tr>
                                                        <td width="10%">{{ format_date($referral->date) }}</td>
                                                        <td width="10%"><a
                                                                href="{{route('transactions.show',$referral->transaction_id)}}">{{$referral->transaction_id}}</a>
                                                        </td>
                                                        <td width="40%">{{$referral->description}}</td>
                                                        <td width="10%">{{$referral->points}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4">No record</td>
                                                    </tr>
                                                @endforelse

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .due-amount .card-body {
            padding: 10px;
        }
    </style>
    <style>
        .list-group-item {
            cursor: pointer;
        }

        .tt-menu {
            width: 100% !important;
        }

        .typeahead {
            positin: relative;
        }

        .Typeahead-spinner {
            position: absolute;
            right: 93px;
            top: 23px;
            display: none;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

@endsection
@section('page-script')

    <script>

        $(function () {
            $("#senderIds").carousel({
                interval: false
            });
            $('#SenderPopUpImage1').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                var imgSrc = button.data('img');
                var imgSrc1 = button.data('img1');

                if (imgSrc == '') {
                    $("#noSenderImage").html('No Image Uploaded');
                }

                var modal = $(this);

                modal.find('.modal-body #senderIds #sender-first-image').attr('src', imgSrc);
                if (imgSrc1 != '') {
                    modal.find('.modal-body #senderIds #sender-second-image').attr('src', imgSrc1);
                } else {
                    modal.find('.modal-body #senderIds .item-2').remove();
                    modal.find('.modal-body #senderIds .next-controls').remove();
                }
            });


            var date = new Date();


            $('input[name="expiry_date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,

                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                minDate: date
            });

            $('input[name="expiry_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY'));
            });

            $('input[name="expiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        });

        $(document).on("submit", "#email-send", function (event) {
            var formData = $(this).serialize();
            var url = $(this).attr('action');
            $(this).find('.has-error').removeClass('has-error');
            $(this).find('label.error').remove();
            $(this).find('.callout').remove();
            $("#email-sendPopUp").html('Sending...');
            $("#email-sendPopUp").attr('disabled', true);

// process the form
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                encode: true
            }).done(function (result) {
                console.log(result);
                if (result.status == 1) {
                    $('.modal').modal('hide');
                    toastr.success(result.data.response_message, {
                        closeButton: true,
                        progressBar: true,
                    });
                    //  $('.box-primary').before(notify('success', 'Beneficiary Added Successfully!'));
                } else {
                    console.log(result)
                    $.each(result.data.errors, function (i, v) {
                        $('#email-send').find('#error-' + i).after('<label class="error ">' + v + '</label>').closest('.form-group').addClass('has-error');
                        $("#email-sendPopUp").attr('disabled', false);
                        $("#email-sendPopUp").html('Send');
                    });
                }
                setTimeout(function () {
                    $('.callout').remove()
                }, 2500);
            });
            event.preventDefault();
        });
    </script>
    <script>
        $('#identificationModal').on('hidden.bs.modal', function (e) {

            $("#form_identification_update")[0].reset();
        });

        var countries = <?php echo getCountryListObject() ?>;
        var states = <?php echo AusStates() ?>;

        var options_update = '';
        var options_update1 = '';
        var id_type_sender_update = "{{$sender->id_type}}";
        var issued_by_sender_update = "{{$sender->issued_by}}";

        $.each(countries, function (key, val) {

            if (issued_by_sender_update == val.name) {
                options_update += '<option value="' + val.name + '" selected="selected">' + (val.name === "Nepal" ? "Nepal,MOFA" : val.name) + '</option>';
            } else {
                if (val.id == 154) {
                    selec = 'selected';
                } else {
                    selec = '';
                }
                options_update += '<option value="' + val.name + '" ' + selec + '>' + (val.name === "Nepal" ? "Nepal,MOFA" : val.name) + '</option>';
            }
        });
        $.each(states, function (key, val) {

            if (issued_by_sender_update == val.name) {
                options_update1 += '<option value="' + val.name + '" selected="selected">' + val.name + '</option> <br>';
            } else {

                options_update1 += '<option value="' + val.name + '" >' + val.name + '</option> <br>';
            }


        });

        if (id_type_sender_update == 1) {
            $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update + '</select>');


        } else if (id_type_sender_update == 2) {
            $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update1 + '</select>');

        } else if (id_type_sender_update == 3) {
            $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update1 + '</select>');
        }


        $(document).on("change", "#form_identification_update #id_type", function () {

            var update_id_type = $(this).val();

            if (update_id_type == 1) {
                $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update + '</select>');


            } else if (update_id_type == 2) {
                $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update1 + '</select>');

            } else if (update_id_type == 3) {
                $("#form_identification_update #issued_by").html('<select class="form-control" name="issued_by">' + options_update1 + '</select>');
            }
        })
    </script>
@endsection

