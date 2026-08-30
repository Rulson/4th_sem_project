@extends('layouts.main')
@section('title', 'Transaction')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Transaction</li>
    <li class="breadcrumb-item active">Show</li>
@stop
@section('content')
    <style>
        .label-comment {
            right: 10px;
            line-height: 0.9;
            padding: 2px 3px;
            position: absolute;
            top: 39px;

        }

        .bg-blue-light {
            background: rgba(44, 59, 65, 0.5);
            color: #fff;
        }

        .bg-blue-dark {
            background: #2c3b41;
            color: #fff;
        }

        .no-box-shadow {
            box-shadow: none
        }

        .b-0 {
            border: 0;
        }

        .m-0 {
            margin: 0
        }

        .vr-bar {
            position: relative;
        }

        .vr-bar:after {
            content: '';
            position: absolute;
            background: white;
            display: block;
            top: 6px;
            right: 0px;
            bottom: 6px;
            width: 2px;
        }

        .table.recent-transactions > tbody > tr > td, .table.recent-transactions > tbody > tr > th {
            vertical-align: middle;
            text-align: center;
        }

        .table.recent-transactions > tbody > tr > td:first-child, .table.recent-transactions > tbody > tr > th:first-child {
            text-align: left;
        }

        .row {
            position: relative;
        }

        .table.recent-transactions > tbody > tr > td:last-child, .table.recent-transactions > tbody > tr > th:last-child {
            text-align: right;
        }

        #paymentNotesTextAreaField:focus-within {
            position: absolute;
            right: 0;
            z-index: 999;
        }

        #paymentNotesTextAreaField:focus-within textarea {

            height: 100px;
        }

        #actionButtonsForPaymentNotes {
            display: none;
        }

        #paymentNotesTextAreaField:focus-within #actionButtonsForPaymentNotes {
            display: block !important;
        }

    </style>

    <div class="modal fade sendingmodal" id="sendmoney-modal" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    @include('flash::message')
    <section class="content">
        <div class="card-group mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="h4 mb-0">{{format_id($transaction['id'],'T')}}    <span style="margin-top: -9px;" class="pull-right">      @if($previous != null)
                                <a href="{{route('transactions.show',$previous)}}" class="btn btn-primary btn-sm " title="previous"><i class="fa fa-arrow-left"></i></a>
                       @endif
                            @if($next != null)
                            <a href="{{route('transactions.show',$next)}}" class="btn btn-primary btn-sm" title="Next"><i class="fa fa-arrow-right"></i></a>
              @endif  </span>    </div>
                    <small class="text-muted text-uppercase font-weight-bold">Transaction id</small>

                   @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)

                        <a style="margin-right: 0px;"
                           class="btn btn-primary btn-sm btn-flat pull-right"
                           data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                           data-keyboard="false"
                           data-url="{{route('transaction.transaction.edit',$transaction['id'])}}">Edit</a>
                    @endif
                    <div class="h4 mb-0"> {{format_only_date($transaction['transaction_date'])}}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Date</small>


                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 4)

                        <form action="{{route('transactions.post.changeStatus',[$transaction['id']])}}"
                              method="post" class="info-box-text">
                            {{csrf_field()}}
                            <div class="input-group">
                                <select class="" style="width: 80%;color: #000" tabindex="-1"
                                        aria-hidden="true" name="status_id">
                                    @foreach($transaction_status as $key=>$value)
                                        <option value="{{$value->id}}"{{$value->id == $transaction['transaction_status_id'] ? 'selected' : ''}}>{{$value->name}}</option>
                                    @endforeach

                                </select>
                                <span class="input-group-btn">
                                                            <button type="submit"
                                                                    class="btn btn-success btn-sm btn-flat">Apply</button>
                                                        </span>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5><b>Documents</b></h5>
                    <div class="row">

                        <div class="col-md-12">
                            <ul style="list-style: none; padding: 0px;">
                                @foreach($documents as $document)
                                    @php  $transactionReceiptDocument = App\Modules\Transaction\Models\TransactionDocument::where('id', $document->id)->first();
                                    @endphp
                                    <li style="margin-bottom: 10px;">
                                        <span>{{$document->file_name}}</span><span
                                                class="pull-right">
                                                               <a data-toggle="modal"
                                                                  data-target="#TransactionReceiptImage"
                                                                  data-img="{{asset('TransactionIdentification/'.$transactionReceiptDocument->file_name)}}"
                                                                  class="btn btn-sm btn-primary"><i
                                                                           class="fa fa-eye"></i></a>
                                                           <a class="btn btn-sm btn-primary"
                                                              href="{{route('transaction.receipt.download',$document->id)}}"><i
                                                                       class="fa fa-download"></i></a>
                                                           </span>
                                    </li>
                                @endforeach

                            </ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">

                    <ul class="list-group">
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            Sending Amount
                            <span class="badge badge-primary badge-pill"> AUD {{$transaction['sending_amount']}}</span>
                        </li>
                        @if(!(empty($coupon_usage) && empty($referrals)))
                            @if(!empty($coupon_usage))
                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Coupon Discount
                                    <span class="badge badge-primary badge-pill">{{isset($coupon_usage->coupon) ? ('Coupon #'.$coupon_usage->coupon_id.' '.$coupon_usage->coupon->name.' '. $coupon_usage->coupon->discount_value. ($coupon_usage->coupon->discount_unit == 'p' ? '%':'$').' discount') : ''}}  </span>
                                </li>
                            @endif
                            @if(!empty($referrals))
                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Referral Points Redeemed
                                    <span class="badge badge-primary badge-pill"> {{$referrals->points}} points</span>
                                </li>
                            @endif
                        @endif
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            @if($freeServiceChargeClaimed)
                                Service Charge (Free service charge from referral)
                            @else
                                Service Charge
                            @endif
                            <span class="badge badge-primary badge-pill">AUD {{$transaction['service_charge']}}</span>
                        </li>
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            Total
                            <span class="badge badge-primary badge-pill">AUD {{$transaction['total_to_pay']}}</span>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card">

                    <ul class="list-group">
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            Exchange Rate
                            <span class="badge badge-primary badge-pill">{{$transaction['exchange_rate']}}</span>
                        </li>
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            Payment Amount
                            <span class="badge badge-primary badge-pill">NPR {{$transaction['payment_amount']}}</span>
                        </li>
                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                            Method of Payment
                            <span class="badge badge-primary badge-pill">{{$transaction['payment_type']}}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if(!in_array(Auth::user()->level_id, [6, 7]))
            <div class="col-md-4" id="paymentNotesTextAreaField">
                <div class="card">

                    <form action="{{route('transaction.staffNotes.store',$transaction['id'])}}"
                          method="post">
                        {{csrf_field()}}
                        <nav>
                            <ul class="list-group">

                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    Payment Notes
                                </li>
                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">

                                    <div class="form-group col-md-12" style="margin-bottom: 0;padding: 4px;">
                                                    <textarea name="staff_notes" class="form-control"
                                                              placeholder="Enter notes here">{{$transaction->staff_notes}}</textarea>
                                    </div>

                                </li>

                                <li class="nav-item" id="actionButtonsForPaymentNotes">
                                    <a class="clearfix">
                                                <span class="pull-right">
                                                    <button type="submit"
                                                            class="btn btn-primary btn-sm btn-flat">Update</button>
                                                </span>
                                    </a></li>
                            </ul>
                        </nav>
                    </form>
                </div>
            </div>
            @endif

        </div>
        <div class="row">
            @if(Auth::user()->level_id !=4)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <b>Sender Details</b>
                                    @if(Auth::user()->level_id==1 || Auth::user()->level_id == 2)
                                        <a class="btn btn-sm btn-primary" data-toggle="modal"
                                           data-target="#sendmoney-modal"
                                           data-url="{{route('transaction.sender.edit',$sender->sender_id)}}">Edit</a>
                                    @endif
                                </div>
                                @if(Auth::user()->level_id==1 || Auth::user()->level_id == 2)
                                    <div class="col-md-6">
                                        <div class="">
                                            <form action="{{route('transaction.post.changesenderstatus', [$sender->sender_id])}}"
                                                  method="post" class="info-box-text">
                                                {{csrf_field()}}
                                                <div class="input-group" style="flex-wrap: nowrap;">

                                                    <select class="" style="width: 80%;color: #000" tabindex="-1"
                                                            aria-hidden="true" name="status">
                                                        @foreach($status as $value)
                                                            <option value="{{$value->id}}"{{$value->id == $sender->sender_status_id ? 'selected' : ''}}>{{$value->name}}</option>
                                                        @endforeach
                                                    </select>
                                                   <span class="input-group-btn">
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-success">Apply</button>
                                                        </span>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0px!important;">
                            <ul class="list-group list-group-unbordered m-0">
                                <li class="list-group-item b-0">
                                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <strong> Total Sent Amount :
                                            AUD {{SenderTotalSendingAmount($sender->sender_id)['sendingAmount']}} </strong>
                                        <br>
                                    @endif
                                    {{$sender['full_name']}}, {{format_id($sender->sender_id,"S")}} <br>
                                    {{$sender['dob']}} <br>
                                    {{$sender['number']}}| {{$sender['email']}} <br>
                                    {{$sender['street']}} {{$sender['suburb']}} {{$sender['postcode']}} {{$sender['state']}}
                                </li>
                                <li class="list-group-item b-0">
                                    Identification Detail <br>
                                    {{$sender['name']}} {{$sender['id_number']}} | {{$sender['issued_by']}}
                                    <br>
                                    Expiry Date:{{$sender['expiry_date']}}


                                    <span class="pull-right">
                                        <a data-toggle="modal" data-target="#SenderPopUpImage" data-img="{{$first_doc}}" data-img1="{{$second_doc}}">
                                            <button type="button" class="buttons">View</button>
                                        </a>
                                        <a href="{{route('identification.get',[$sender_id])}}">
                                            <button type="button" class="buttons">Download</button>
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
            @endif
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">

                                <b>Beneficiary Details</b>
                                @if(Auth::user()->level_id==1 || Auth::user()->level_id == 2)
                                    <a class="btn btn-sm btn-primary btn-flat " data-toggle="modal"
                                       data-target="#sendmoney-modal"
                                       data-url="{{route('transaction.beneficiary.edit',$beneficiary->beneficiary_id)}}">Edit</a>
                                    <a class="btn btn-sm btn-primary pull-right btn-flat" data-toggle="modal"
                                       data-target="#sendmoney-modal"
                                       data-url="{{route('change.beneficiary',[$sender->sender_id,$beneficiary->beneficiary_id,$transaction['id']])}}">Change
                                        Receiver</a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0px!important;">
                        <ul class="list-group list-group-unbordered m-0">

                            <li class="list-group-item b-0">
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                    <strong>Total Received Amount :
                                        NPR {{BeneficiaryTotalReceivedAmount($beneficiary->beneficiary_id)}} </strong>
                                    <br>@endif
                                {{$beneficiary['full_name']}}, {{format_id($beneficiary->beneficiary_id,"B")}} <br>
                                {{$beneficiary['number']}}<br>
                                {{$beneficiary['street']}}, {{$beneficiary['suburb']}}, {{$beneficiary['postcode']}}
                                , {{$beneficiary['state']}} <br>

                            </li>
                            <li class="list-group-item b-0">
                                Account Detail <br>
                                {{$beneficiary['accountName']}} | A/C No: {{$beneficiary['accountNo']}} <br>
                                {{$beneficiary['bankName']}} {{$beneficiary['bsb']}}

                            </li>
                        </ul>
                    </div>


                </div>
            </div>
            <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">

                                    <b>Transaction History</b>

                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0px!important;">
                            <ul class="list-group">

                                @foreach($transaction_histories as $tran_history)
                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                    {{ $tran_history->status }}
                                    <span class="badge badge-primary badge-pill">{{ date('d M Y H:i A', strtotime($tran_history->updated_at)) }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>


                    </div>
                </div>
        </div>

    </section>

    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                @include('Transaction::modals/addCommentModal',['transaction_id'=>34])
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="SenderPopUpImage" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
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
    <div class="modal" id="TransactionReceiptImage" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="imgSrc" width="100%">
                    <span id="noReceipt"></span>
                </div>

            </div>
        </div>
    </div>



@endsection
@section('page-script')
   @include('layouts.common')
    <script>
        $('.sendingmodal').on('hidden', function () {
            clear();
        });
        $("#senderIds").carousel({
            interval: false
        });
        $('#SenderPopUpImage').on('show.bs.modal', function (event) {
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
        $('#TransactionReceiptImage').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var imgSrc = button.data('img');
            if (imgSrc == '') {
                $("#noReceipt").html('Image not supported ,please download it');
            }
            var modal = $(this);

            modal.find('.modal-body #imgSrc').attr('src', imgSrc);

        });
        $(document).on("submit", "#editbeneficiary", function (event) {
            var formData = $(this).serialize();
            var url = $(this).attr('action');
            $(this).find('.has-error').removeClass('has-error');
            $(this).find('label.error').remove();
            $(this).find('.callout').remove();
// process the form
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                encode: true
            })
                .done(function (result) {
                    if (result.status == 1) {
                        $('.modal').modal('hide');
                        toastr.success('Beneficiary has been changed Successfully!', {
                            closeButton: true,
                            progressBar: true,
                        });
                        //   $('.box-primary').before(notify('success', 'Beneficiary Updated Successfully!'));
                    } else {
                        $('.modal').modal('hide');
                        toastr.error('Something went wrong!', {
                            closeButton: true,
                            progressBar: true,
                        });
                        // $('.box-primary').before(notify('danger', 'Something went wrong!'));
                    }
                    setTimeout(function () {
                        $('.callout').remove()
                    }, 2500);
                });
            event.preventDefault();
        });

    </script>
@endsection







