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
                        <a class="carousel-control-prev next-controls" href="#senderIds" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next next-controls" href="#senderIds" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <span id="noSenderImage"></span>
                </div>

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

                                    <a type="button" data-toggle="modal" data-target="#money-transfer-modal"
                                       data-url="{{route('transaction.beneficiary.add').'?id='.$sender->sender_id}}"
                                       id="add_ben" class="btn btn-success btn-sm">Add New Beneficiary</a>

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

                                        <a type="button" data-toggle="modal"
                                           data-url="{{route('sender.add.address',$sender->sender_id)}}"
                                           title="Add new address"
                                           data-target="#money-transfer-modal"
                                           class="btn btn-primary btn-sm pull-right"><i
                                                    class="fa fa-plus"></i></a>
                                    
                                    <dl>
                                        <dt>Address</dt>
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
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <strong>Identification Details</strong>
                            <div class="pull-right">


                                    <a type="button" data-toggle="modal"
                                       data-url="{{route('sender.add.new.identification',$sender->sender_id)}}"
                                       title="Add new identification"
                                       data-target="#money-transfer-modal"
                                       class="btn btn-primary btn-sm"><i
                                                class="fa fa-plus"></i></a>

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
                                        <a data-toggle="modal" data-target="#SenderPopUpImage1" data-img="{{$first_doc}}" data-img1="{{$second_doc}}">
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
                                            <a class="nav-link" data-toggle="tab" href="#addresses" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Addresses</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#identifications" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Identifications</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#coupons" role="tab"
                                               aria-controls="coupons"><i class="fa fa-money"></i> Coupon Usage</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#referrals" role="tab"
                                               aria-controls="referrals"><i class="fa fa-money"></i> Referrals</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="transactions" role="tabpanel">

                                            <div class="" style="overflow-x: auto;">

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
                                            <div class="" style="overflow-x: auto;">


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
                                                                <a href="{{route('beneficiary.edit',[$value['beneficiary_id']])}}"
                                                                   title="Edit" class="btn btn-sm btn-primary"><i
                                                                            class="fa fa-edit"></i></a>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="addresses" role="tabpanel">
                                            <div class="" style="overflow-x: auto;">
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
                                            <div class="" style="overflow-x: auto;">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="10%">Identification Id</th>
                                                    <th width="15%">Id Type</th>
                                                    <th width="20%">Issued By</th>
                                                    <th width="20%">Id Number</th>
                                                    <th>Expiry Date</th>
                                                    <th>Status</th>


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

                                                    </tr>

                                                @endforeach

                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="coupons" role="tabpanel">
                                            <div class="card" style="overflow-x: scroll;">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th width="10%">Date </th>
                                                        <th width="10%">Coupon </th>
                                                        <th width="10%">Code </th>
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
                                                            <td><a href="{{route('transactions.show',$coupon->transaction_id)}}">{{$coupon->transaction_id}}</a></td>
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
                                                            <div class="text-muted text-uppercase font-weight-bold font-xs">
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
                                                            <div class="text-muted text-uppercase font-weight-bold font-xs">
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
                                                            <div class="text-muted text-uppercase font-weight-bold font-xs">
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
                                                        <td width="10%">
                                                            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)

                                                                    <a href="{{route('transactions.show',$referral->transaction_id)}}">
                                                                        {{$referral->transaction_id}}
                                                                    </a>
                                                                @else
                                                                    @if($referral->points < 0)
                                                                        <a href="{{route('transactions.show',$referral->transaction_id)}}">
                                                                            {{$referral->transaction_id}}
                                                                        </a>
                                                                    @else
                                                                        {{$referral->transaction_id}}
                                                                    @endif
                                                            @endif
                                                        </td>
                                                        <td width="40%">{{$referral->description}}</td>
                                                        <td width="10%" style="text-align:center;">{{$referral->points}}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="4">No record</td></tr>
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
        .nav-tabs{
            border: 1px solid #a4b7c1;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            background: #f2f2f2;
        }
        .tab-content .tab-pane {
            padding: 0!important;
        }
        .due-amount .card-body {
            padding: 10px;
        }
        .table{
            margin-bottom: 0rem;
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
            $('#SenderPopUpImage1').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                var imgSrc = button.data('img');
                var imgSrc1 = button.data('img1');

                if (imgSrc == '') {
                    $("#noSenderImage").html('No Image Uploaded');
                }

                var modal = $(this);

                modal.find('.modal-body #senderIds #sender-first-image').attr('src', imgSrc);
                if(imgSrc1 != ''){
                    modal.find('.modal-body #senderIds #sender-second-image').attr('src', imgSrc1);
                }else{
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
@endsection

