@extends('layouts.main')
@section('title', ' Advanced Search')
@section('breadcrumb')
    @parent

    <li class="breadcrumb-item">Transactions</li>
    <li class="breadcrumb-item active">Advanced Search</li>
@stop
<style>

    .label-comment {
        right: -10px;
        line-height: 0.9;
        padding: 2px 3px;
        position: absolute;
        top: -8px;

    }
    </style>
@section('content')
    <div class="card">
        <div class="card-body">
              <form action="{{route('transactions.orders.post')}}" method="post" id="search-form">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Filtered Options <input type="submit" class="btn btn-primary pull-right" value="Search">
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label>Transaction Status</label>
                                <select name="transactionStatusId[]"
                                        class="form-control select2-multiple-transaction-status select2-multiple"  multiple="">
                                    @foreach($status as $key=>$status)
                                        <option value="{{$status->id}}" <?php  if (isset($search_attributes['transaction_status'])):
                                        for ($i = 0; $i <= (count($search_attributes['transaction_status']) - 1); $i++):
                                        echo ($search_attributes['transaction_status'][$i] == $status->id) ? 'selected' : '';
                                        endfor;
                                        endif;
                                        ?>>{{$status->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Sender Name</label>
                                <input type="text" class="form-control pull-right" name="senderName" id="sender_name"
                                       value="@if(isset($search_attributes['sender_name'])){{$search_attributes['sender_name']}} @endif"
                                       autocomplete="off">

                            </div>


                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Beneficiary Name</label>
                                <input type="text" class="form-control pull-right" name="beneficiaryName" id="beneficiary_name"
                                       value="@if(isset($search_attributes['beneficiary_name'])){{$search_attributes['beneficiary_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>
                    </div>
                    <div class="row">
                        @if(in_array(Auth::user()->level_id,[1,2,3,6,7,8]))
                            <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label for="">Added By</label>

                                    <select name="addedBy" class="form-control  js-states select2-added-by">
                                        <option value="0">All</option>

                                        @foreach($added_by as $user)
                                            <option value="{{$user['id']}}"
                                            <?php  if (isset($search_attributes['added_by'])):
                                                    echo ($search_attributes['added_by'] == $user['id']) ? 'selected' : '';

                                            endif;
                                                ?>>
                                                {{$user['first_name']}} {{$user['last_name']}}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        @endif

                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>Transaction Date From:</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                    <input type="text" class="form-control pull-right" name="from" id="from" value="@if(isset($search_attributes['from'])){{$search_attributes['from']}} @endif" autocomplete="off">

                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>Transaction Date To:</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                    <input type="text" class="form-control pull-right" name="to" id="to" value="@if(isset($search_attributes['to'])){{$search_attributes['to']}} @endif" autocomplete="off">

                                </div>
                            </fieldset>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Distributor</label>
                                <select name="distributorId" class="form-control  js-states select2-added-by">
                                    <option value="0">All</option>
                                    @foreach($distributors as $distributor)
                                        <option value="{{$distributor['id']}}"
                                        <?php  if (isset($search_attributes['distributor'])):
                                            echo ($search_attributes['distributor'] == $distributor['id']) ? 'selected' : '';

                                        endif;
                                            ?>>
                                            {{$distributor['company_name']}}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Sending Amount [AUD]</label>
                                <input type="text" class="form-control pull-right" name="sendingAmnt" id="sending_amount"
                                       value="@if(isset($search_attributes['sending_amount'])){{$search_attributes['sending_amount']}} @endif"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control" name="payment_type" id="payment_type">
                                    <option value="">Please Select</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Local Remit">Local Remit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(in_array(Auth::user()->level_id, [1,2,3,6,7]))
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Transaction ID From:</label>
                                <input type="text" class="form-control pull-right" name="id_from" id="id_from"
                                       value="@if(isset($search_attributes['id_from'])){{$search_attributes['id_from']}} @endif"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="transaction_status">Transaction ID To:</label>
                                <input type="text" class="form-control pull-right" name="id_to" id="id_to"
                                       value="@if(isset($search_attributes['id_to'])){{$search_attributes['id_to']}} @endif"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                    @endif
                </form>

            </div>
            <div class="card-body">
                <h3>Filtered Applications</h3>
                <hr>

                @if(isset($transactions))
                    @if(in_array(Auth::user()->level_id,[1,2]))
                    <button class="btn btn-primary pull-right" id="excel-export" style="font-size: 15px;">
                        Export
                    </button>
                    @endif
                    <br>
                    <table id="exampleTransactionSearch" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all"/>
                            </th>
                            <th>Trans Id</th>
                            <th>Date</th>
                            <th>Sender Name</th>
                            <th>Beneficiary Name</th>
                            <th>Sending Amount(AUD)</th>
                            <th>Payment Amount (NPR)</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($transactions as $key=>$value)
                            <tr>
                                <td><input name="checks" type="checkbox" value="{{$value['id']}}" class="checks"></td>
                               @if(Auth::user()->level_id !=4)
                                    <td>{{format_id($value['transactionId'],'T')}}</td>
                                @else
                                    <td>{{format_id($value['transactionId'],'T')}}</td>
                                @endif
                                <td>{{standard_date($value['transaction_date'])}}</td>
                                <td><a href="{{route('sender.show',$value['sender_id'])}}">{{getSenderName($value['sender_id'])}}</a></td>
                                <td>{{getBeneficiaryName($value['beneficiary_id'])}}</td>
                                <td>{{$value['sending_amount']}}</td>
                                <td>{{$value['payment_amount']}}</td>
                                <td>{{get_user_name($value['added_by'])}}</td>

                                <td>
                                    @php
                                        $agent = \App\Modules\Agent\Models\Agent::where('user_id',$value['added_by'])->first();
                                     $user = App\Modules\User\Models\User::join('person','person.id','=','users.person_id')
          ->where('users.id','=',$value['added_by'])->select('person.first_name','person.last_name','person.email')->first();
                                    $client = App\Modules\User\Models\User::where('level_id',5)->where('id',$value['added_by'])->first();
                                    @endphp

                                    <a href="{{route('transactions.show',$value['id'])}}"
                                       data-toggle="tooltip" data-placement="bottom" title="View"
                                       class="btn btn-sm btn-success"><i
                                                class="fa fa-eye"></i></a>
                                    @if(in_array(Auth::user()->level_id,[1,2,3,5]))
                                
                                        @if(isset($agent))
                                            <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="{{$value['transactionId']}}"  data-agent="{{$agent['user_id']}}" data-user="{{$user}}"  data-toggle="modal" data-target="#comment-modal" 
                                                class="btn btn-sm btn-success"><i
                                                            class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">{{getUnreadNotificationCount($value['transactionId'])}}</span></span>
                                        @elseif(isset($client))
                                            <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="{{$value['transactionId']}}"  data-client="{{$client['id']}}" data-user="{{$user}}"  data-toggle="modal" data-target="#comment-modal" 
                                                class="btn btn-sm btn-success"><i
                                                            class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">{{getUnreadNotificationCount($value['transactionId'])}}</span></span>
                                        @else

                                            <span style="position:relative;" data-toggle="tooltip" title="Add Comment" data-placement="left"><button data-transaction="{{$value['transactionId']}}" data-agent="" data-client="" data-toggle="modal" data-target="#comment-modal" 
                                                class="btn btn-sm btn-success"><i
                                                            class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">{{getUnreadNotificationCount($value['transactionId'])}}</span></span>
                                        @endif
                                    @endif


                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                        @if(in_array(Auth::user()->level_id,[1,2]))

                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="row">
                                        <div class="">
                                            <span class="btn btn-primary">Convert To</span>

                                        </div>
                                        <!--/.col-->
                                        <div class="">
                                            <select id="statusChangeSelect2" name="status" class="form-control" style="width: 80%;">
                                                <option>Choose status</option>
                                                <option value="1">Unconfirmed</option>
                                                <option value="2">Confirmed</option>
                                                <option value="3">Send For Collection</option>
                                                <option value="4">Payment In Progress</option>
                                                <option value="5">Delivered</option>
                                                <option value="6">Cancelled</option>
                                                <option value="7">Onhold</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <button class="btn btn-success" id="changeStatusButton">Apply</button>

                                        </div>
                                        <!--/.col-->
                                    </div>

                                </div>
                            </div>

                        @endif

                @else
                    <div class="alert alert-warning" role="alert">
                        <h4>No Filtered Records!</h4>

                        <p>You can search for the applications by providing the details in the form.</p>
                    </div>

                @endif


            </div>
    </div>
    </div>

    <div class="modal fade" id="comment-modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('Transaction::modals/addCommentModal',['transaction_id'=>34])
                </div>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    @include('layouts.common')

    <script type="text/javascript">
       $('input[name="from"]').daterangepicker({
           singleDatePicker: true,
           showDropdowns: true,
           minYear: 1901,
           autoUpdateInput: false,
           locale: {
               cancelLabel: 'Clear'
           }
       });
       $('input[name="from"]').on('apply.daterangepicker', function (ev, picker) {
           $(this).val(picker.startDate.format('DD/MM/YYYY'));
       });

       $('input[name="from"]').on('cancel.daterangepicker', function (ev, picker) {
           $(this).val('');
       });
       $('input[name="to"]').daterangepicker({
           singleDatePicker: true,
           showDropdowns: true,
           minYear: 1901,
           autoUpdateInput: false,
           locale: {
               cancelLabel: 'Clear'
           },
       });
       $('input[name="to"]').on('apply.daterangepicker', function (ev, picker) {
           $(this).val(picker.startDate.format('DD/MM/YYYY'));
       });

       $('input[name="to"]').on('cancel.daterangepicker', function (ev, picker) {
           $(this).val('');
       });
        $('.select2-multiple-transaction-status').select2({
           // placeholder:'Choose Status'
            theme:"bootstrap"
        });

        $('.select2-multiple-sender').select2({
            theme:"bootstrap",
            minimumInputLength:3,
            ajax: {
                url: 'senders/getSendersDropDownDataForSearch',
                dataType: 'json',
                delay: 1000,
                processResults: function(result){
                    return {
                        results:result
                    }
                }
            }
        });

        $('.select2-multiple-beneficiary').select2({
            theme:"bootstrap",
            minimumInputLength:3,
            ajax: {
                url: 'beneficiaries/getBeneficiariesDropDownDataForSearch',
                dataType: 'json',
                delay: 1000,
                processResults: function(result){
                    return {
                        results:result
                    }
                }
            }
        });
    </script>
    @endsection







