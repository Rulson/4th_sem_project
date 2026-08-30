@extends('layouts.main')
@section('title', 'Beneficiaries')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Beneficiaries</li>
    <li class="breadcrumb-item active">{{getBeneficiaryName($beneficiary->beneficiary_id)}}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="sendmoney-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
       <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Additional Account</h3>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form method="post"
                      action="{{route('addition.account.add', [$beneficiary->beneficiary_id])}}"
                      id="my_form_additional_account" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Account Details</strong>
                                        </div>
                                        <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="accountNameDiv"
                                                     class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                                    <label>Account Name (*) </label>

                                                    <input type="text" name="account_name"
                                                           class="form-control"
                                                           id="account_name"
                                                           placeholder="Enter Account Name">

                                                </div>
                                                @if($errors->has('account_name'))
                                                    {!! $errors->first('account_name', '<label class="control-label"
                                                                                           for="inputError">:message</label>') !!}
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div id="accountNoDiv"
                                                     class="form-group @if($errors->has('account_name')) {{'has-error'}} @endif">
                                                    <label>Account Number (*) </label>
                                                    <input type="text" name="account_no"
                                                           class="form-control" id="account_no"
                                                           placeholder="Enter Account Number">

                                                </div>
                                                @if($errors->has('account_no'))
                                                    {!! $errors->first('account_no', '<label class="control-label"
                                                                                           for="inputError">:message</label>') !!}
                                                @endif
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="bsbDiv"
                                                     class="form-group @if($errors->has('bsb')) {{'has-error'}} @endif">
                                                    <label>BSB / Branch (*) </label>
                                                    <input type="text" name="bsb"
                                                           class="form-control"
                                                           id="bsb"
                                                           placeholder="Enter BSB/Branch">

                                                </div>
                                                @if($errors->has('bsb'))
                                                    {!! $errors->first('bsb', '<label class="control-label"
                                                                                           for="inputError">:message</label>') !!}
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div id="bankNameDiv"
                                                     class="form-group @if($errors->has('bank_name')) {{'has-error'}} @endif">
                                                    <label>Bank Name (*) </label>

                                                    <input type="text" name="bank_name"
                                                           class="form-control"
                                                           id="bank_name"
                                                           placeholder="Enter Bank Name">

                                                </div>
                                                @if($errors->has('bank_name'))
                                                    {!! $errors->first('bank_name', '<label class="control-label"
                                                                                           for="inputError">:message</label>') !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <input type="submit" value="submit"
                               class="btn btn-primary">
                    </div>
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
                            <strong>Beneficiary Details</strong>
                            <div class="pull-right">
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)

                                        <a href="{{route('beneficiary.edit',$beneficiary->beneficiary_id)}}"
                                           class="btn btn-success  btn-sm pull-right"><i
                                                    class="fa fa-pencil">
                                                Edit Beneficiary</i></a>

                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Beneficiary Id</dt>
                                        <dd>{{format_id($beneficiary->beneficiary_id,'B')}}</dd>
                                        <dt>Created By</dt>
                                        <dd>{{get_user_name($beneficiary->added_by)}}</dd>
                                        <dt>Created Date</dt>
                                        <dd>{{standard_date($beneficiary->dateAdded)}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>Beneficiary Name</dt>
                                        <dd>{{$beneficiary->first_name}} {{$beneficiary->last_name}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>Phone</dt>
                                        <dd>{{$beneficiary->number}}</dd>


                                    </dl>
                                </div>

                                <div class="col-md-3">
                                    <dl>
                                        <dt>Address</dt>
                                        <dd>{{$beneficiary->street}},{{$beneficiary->suburb}} ,{{$beneficiary->postcode}}
                                            ,{{$beneficiary->state}},{{$beneficiary->country}}
                                        </dd>
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
                            <strong>Account Details</strong>
                            <div class="pull-right">

                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 ||Auth::user()->level_id == 3 || Auth::user()->level_id == 5)
                                    <span class="pull-right"><a
                                                type="button" class="btn btn-primary btn-sm pull-right"
                                                data-toggle="modal"  title="Add Additional Account"
                                                data-target="#addAccountModal"><i
                                                    class="fa fa-plus"></i></a></span>
                                @endif
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )
                                    <a type="button" data-toggle="modal"
                                       data-url="{{route('change.account',$beneficiary->beneficiary_id)}}"
                                       title="Change Default Account"
                                       data-target="#sendmoney-modal"
                                       class="btn btn-primary btn-sm pull-right"><i
                                                class="fa fa-edit"></i></a>

                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <dl>
                                <dt>Account Name</dt>
                                <dd>{{$beneficiary->account_name}}</dd>
                                <dt>Account Number</dt>
                                <dd>{{$beneficiary->accountNo}}</dd>
                                <dt>Bsb / Branch</dt>
                                <dd>{{$beneficiary->bsb}}</dd>
                                <dt>Bank Name</dt>
                                <dd>{{$beneficiary->bankName}}</dd>
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
                                        <div class="h4 mb-0">AUD {{$sumSendingAmount}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">
                                                Total Sending Amount
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
                                        <div class="h4 mb-0 font-lg">NPR {{$sumPaymentAmount}}</div>
                                        <a href="">
                                            <small class="text-muted text-uppercase font-weight-bold">Total Payment Amount
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
                                            <a class="nav-link" data-toggle="tab" href="#accounts" role="tab"
                                               aria-controls="payments"><i class="fa fa-money"></i> Accounts</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="transactions" role="tabpanel">

                                            <div class="card" style="overflow-x: auto;">
                                                <table id="exampleTransaction1"
                                                       class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Transaction ID</th>
                                                        <th>Beneficiary Name</th>
                                                        <th>Sending Amount</th>
                                                        <th>Payment Amount</th>
                                                        <th>Rate</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($transaction as $key=>$value)
                                                        <tr>
                                                            <td>{{format_id($value['transaction_id'],"T")}}</td>
                                                            <td>{{getBeneficiaryName($value['beneficiary_id'])}}</td>
                                                            <td>{{$value['sendingAmount']}}</td>
                                                            <td>{{$value['paymentAmount']}}</td>
                                                            <td>{{$value['exchangeRate']}}</td>
                                                            <td>{{$value['status']}}</td>
                                                            <td><a href="{{route('transactions.show', [$value['transaction_id']])}}"
                                                                   data-toggle="tooltip" title="View"
                                                                   class="btn btn-sm btn-success">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        <div class="tab-pane" id="accounts" role="tabpanel">
                                            <div class="card" style="overflow-x: auto;">


                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Bank Detail Id</th>
                                                        <th>Account Name</th>
                                                        <th>Account Number</th>
                                                        <th>Bsb/Branch</th>
                                                        <th>Bank Name</th>
                                                        <th>Status</th>
                                                        @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                                            <th>Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($allAccounts as $account)

                                                        <tr>
                                                            <td>{{format_id($account->bankDetailId,'BD')}}</td>
                                                            <td>{{$account->accountName}}</td>
                                                            <td>{{$account->accountNo}}</td>
                                                            <td>{{$account->bsb}}</td>
                                                            <td>{{$account->bankName}}</td>
                                                            @if($account->current == 1)
                                                                <td>Active</td>
                                                            @else
                                                                <td>Inactive</td>
                                                            @endif
                                                            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                                                <td><a type="button" data-toggle="modal"
                                                                       data-url="{{route('change.account',$beneficiary->beneficiary_id)}}"
                                                                       title="Change Default"
                                                                       data-target="#sendmoney-modal"
                                                                       class="btn btn-primary btn-sm pull-right"><i
                                                                                class="fa fa-edit"></i></a></td>
                                                            @endif
                                                        </tr>

                                                    @endforeach


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
    </div>
    </div>
    <style>
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
    $(document).on("click", ".buttons", function () {
        var statusid = $(this).data('id');
        $(".modal-body #statusId").val(statusid);
        // As pointed out in comments,
        // it is unnecessary to have to manually call the modal.
        // $('#addBookDialog').modal('show');

        $(function () {
            $("#submit-additional-account").click(function () {
                    var accountNameDiv = $("#accountNameDiv");
                    var accountNoDiv = $("#accountNoDiv");
                    var bsbDiv = $("#bsbDiv");
                    var bankNameDiv = $("#bankNameDiv");

                    if ($("#account_name").val() === '') {
                        accountNameDiv.addClass("has-error");
                        return false;
                    } else {
                        accountNameDiv.removeClass("has-error");

                    }
                    if ($("#account_no").val() === '') {
                        accountNoDiv.addClass("has-error");
                        return false;
                    } else {
                        accountNoDiv.removeClass("has-error");

                    }
                    if ($("#bsb").val() === '') {
                        bsbDiv.addClass("has-error");
                        return false;
                    } else {
                        bsbDiv.removeClass("has-error");

                    }
                    if ($("#bank_name").val() === '') {
                        bankNameDiv.addClass("has-error");
                        return false;
                    } else {
                        bankNameDiv.removeClass("has-error");

                    }
                    $('#my_form_additional_account').submit();
                }
            );
        });
    });
</script>
@endsection

