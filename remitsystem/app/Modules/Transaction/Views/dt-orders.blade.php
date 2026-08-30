@extends('layouts.main')
@section('title', 'All Orders')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">All Orders</li>
@stop
@section('content')
    <style>
    .label-comment {
        right: -10px;
        line-height: 0.9;
        padding: 2px 3px;
        position: absolute;
        top: -8px;

    }
    table.orders td {
        white-space: nowrap;
    }
    </style>
    <div class="animated fadeIn">

        <div class="card">
            <div class="card-header">
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                <a data-toggle="modal" data-target="#sendmoney-modal"
                    data-url="" class="btn btn-sm btn-success" id="update_agent_rate_button">Update Agent Rate</a>
                <a data-toggle="modal" data-target="#sendmoney-modal"
                    data-url="" class="btn btn-sm btn-success" id="update_order_status_button">Update Order Status</a>
                <a data-toggle="modal" data-target="#sendmoney-modal"
                    data-url="" class="btn btn-sm btn-success" id="update_cost_rate_button">Update Cost Rate</a>
                <a data-toggle="modal" data-target="#sendmoney-modal"
                    data-url="" class="btn btn-sm btn-success" id="distributor_assign_multiple_button">Distributor Assign</a>
                @endif

                <div class="pull-right">
                    <input>
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" id="paginateButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$num}}</button>
                            <div class="dropdown-menu" aria-labelledby="paginateButton">
                                <a class="dropdown-item" href="#" onclick="pagecount(10)">10</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(50)">50</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(100)">100</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(300)">300</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(500)">500</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('transactions.search.view')}}" class="btn btn-primary btn-sm">Advanced Search</a>
                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                        <div class="btn-group">
                            <div class="dropdown show">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Export
                                </button>
                                <div class="dropdown-menu " aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" id="excel-export"><b>Export 1</b></a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a href="{{ url('send-money') }}" class="btn btn-primary btn-sm">Add New Order</a>
                </div>
            </div>

            <div class="card-body" style="overflow-x: scroll;">
                <table id="orders-table" class="table table-responsive-sm table-striped table-bordered table-sm orders datatable">
                    <thead >
                        <tr>
                            <th><input type="checkbox" id="check-all"/></th>
                            <th>Action</th>
                            <th>Trans Id</th>
                            <th>Date</th>
                            <th>Status</th>
                            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                <th>Quick Note</th>
                            @endif
                            <th>Sender</th>
                            <th>Sender Pay</th>
                            <th>Receiver </th>
                            <th>Receiver Get</th>
                            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                <th>Distributor</th>
                                <th>Agent</th>
                            @endif
                            <th>Receive Via</th>
                            <th>Receiver Phone</th>
                            <th>Bank Name</th>
                            <th>Bank AC Name</th>
                            <th>Bank AC No</th>
                            <th>Bank AC City</th>
                            <th>Cust Rate</th>
                            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                <th>Agent Rate</th>
                                <th>Agent Commission</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $data)
                        <tr>
                            <td><input type="checkbox" value="{{$data->transaction_id}}" class="checks"/></td>
                            <td>
                                @if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 5)
                                    @php
                                    $agent = App\Modules\Agent\Models\Agent::where('user_id', $data->added_by)->first();
                                    $user = App\Modules\User\Models\User::leftJoin('person', 'person.id', '=', 'users.person_id')->where('users.id', '=', $data->added_by)->select('person.first_name', 'person.last_name', 'person.email')->first();
                                    $client = App\Modules\User\Models\User::where('level_id', 5)->where('id', $data->added_by)->first();
                                    @endphp
                                    <div class="btn-group">
                                        <div class="dropdown">
                                            <button class="btn btn-info btn-sm dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tasks"></i></button>
                                        
                                            @if(isset($agent))
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" onclick="actionbox(1,'{{$data->transaction_id}}','{{$agent['user_id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(2,'{{$data->transaction_id}}','{{$agent['user_id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Receipt</a>
                                            </div>
                                        @elseif (isset($client))
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" onclick="actionbox(1,'{{$data->transaction_id}}','{{$client['id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(2,'{{$data->transaction_id}}','{{$client['id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Receipt</a>
                                            </div>       
                                            @else
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" onclick="actionbox(1,'{{$data->transaction_id}}','','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(2,'{{$data->transaction_id}}','','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                                <a class="dropdown-item" href="#" onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Receipt</a>

                                            </div>
                                        @endif
                                    @endif
                                        </div>
                                    </div>

                                </td>
                                <td>{{$data->transaction_id}}</td>
                                <td>{{format_date_order_page($data->transactionDate)}}</td>
                                <td>
                                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <a data-toggle="modal" data-target="#sendmoney-modal"
                                           data-url="{{route('changeStatus.modal',[$data->transaction_id])}}"
                                           class="badge {{str_replace(' ', '_', strtolower(getStatusName($data->status_id)))}}" title="Status">{{getStatusName($data->status_id)}}</a>
                                    @else
                                        <span class="badge {{str_replace(' ', '_', strtolower(getStatusName($data->status_id)))}}">{{getStatusName($data->status_id)}}</span>
                                    @endif
                                </td>
                                @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                <td>
                                    <form id="add-staffnote-form-{{$data->transaction_id}}"
                                            action="{{route('transaction.adminStaffNotes.store', $data->transaction_id)}}">
                                        {{csrf_field()}}
                                        <span style="min-width:100px;max-width:120px;">
                                            <textarea style="padding: 0px 3px; color:red;height: 28px; width:200px!important; font-size: 13px;" name="admin_staff_notes"
                                                        class="form-control" id="staff_note_textarea" data-txn="{{$data->transaction_id}}"
                                                        placeholder="Quick note">{{getAdminStaffNote($data->transaction_id)}}</textarea>
                                        </span>
                                        <span>
                                        </span>
                                    </form>
                                </td>
                                @endif
                                <td >{{$data->sender_name}}</td>

                                <td>{{$data->totalAmount}}</td>
                                <td>{{$data->beneficiary_name}}</td>
                                <td>{{$data->paymentAmount}}</td>
                                @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                    <td>
                                        @php $assign_distributor = App\Modules\Distributor\Models\DistributorsAssign::where('transactions_id', $data->transaction_id); @endphp
                                        @if ($assign_distributor->count() == 1)
                                            @php 
                                            $assigned_name = $assign_distributor->first();
                                            $company =  App\Modules\Distributor\Models\DistributorOffice::where('id',$assigned_name->distributor_office_id)->first();
                                            $company_name= getDistributorOfficeName($company->companies_id);
                                            @endphp
                                            <a data-toggle="modal" data-target="#sendmoney-modal"
                                               data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                               class="badge badge-warning" title="Distributor">{{$company_name}}</a>
                                        @elseif ($assign_distributor->count() > 1)
                                            <a data-toggle="modal" data-target="#sendmoney-modal"
                                               data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                               class="badge badge-warning" title="Distributor">Multiple</a>
                                        @else
                                            <a data-transaction-id="{{$data->transaction_id}}" data-sending-amount="{{ $data->sendingAmount}}" data-payment-amount="{{ $data->paymentAmount}}" data-toggle="modal"
                                               data-target="#assignDistributor" href="#"
                                               class="badge badge-warning assign-distributor" title="Assign Distributor">Distributor</a>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $agent_transaction = \App\Modules\Agent\Models\AgentTransaction::where('transactions_id',$data->transaction_id)->first();
                                           if($agent_transaction){
                                            $val = getAgentName($agent_transaction->agents_id);
                                            }else{
                                            $val = "Collector";
                                            }
                                        @endphp
                                        <a data-toggle="modal" data-target="#sendmoney-modal"
                                           data-url="{{route('transactions.assign.agent', [$data->transaction_id])}}"
                                           class="badge badge-warning" title="Distributor">{{$val}}</a></td>
                                @endif
                                <td>{{$data->payment_type}}</td>
                                <td>{{$data->beneficiary_phone}}</td>
                                <td>{{$data->bank_name}}</td>
                                <td>{{$data->account_name}}</td>
                                <td>{{$data->account_no}}</td>
                                <td>{{$data->bsb}}</td>
                                <td>{{$data->exchangeRate}}</td>
                                @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2)
                                    <td>{{$data->agentRate}}</td>
                                    <td>{{$data->agentCommission}}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <ul class="pagination">
                        @if(!isset($getStatus))
                        {{$orders->links()}}
                            @else
                            {{ $orders->appends(Request::except('page'))->links() }}
@endif
                    </ul>
                </div>

            </div>
        </div>

    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('Transaction::modals/addCommentModal',['transaction_id'=>34])
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade sendingmodal" id="sendmoney-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    @include('Transaction::modals/assignDistributorModal')

@endsection


@section('page-script')
    @include('layouts.common')
    <script type="text/javascript">
        var AppUrl = "{{url('/')}}";
        var total_amount ;
        function pagecount(count) {
            var url = "{{route('transactions.orders', ['num' => "+count+"])}}";
            window.location= url;
            
        }
        $(document).ready(function () {

            $('#orders-table').dataTable({
                "processing": true,
                "destroy":true,
                "serverSide": true,
                "searching":true,
                "ajax": "/orders/getOrders",
                stateSave: true,
                "columns": [
                    {data: 'beneficiary_id', name: 'beneficiary_id'},
                    {data: 'datejoined', name: 'datejoined', searchable: false},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'number', name: 'number'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
                order: [[0, 'desc']]
            })

            $('#assignDistributor').on('hidden', function() {
                clear();
            });
            $(document).on('click', '.assign-distributor', function () {
                var transactionId = $(this).data('transaction-id');
              //  var sendingAmount = $(this).data('sending-amount');
                var paymentAmount = $(this).data('payment-amount');
                $('input[name="transactionId"]').val(transactionId);
            //    $('input[name="total_amount"]').val(sendingAmount);
                $('input[name="total_amount"]').val(paymentAmount);
              //  $('#form-amount').attr('value', sendingAmount);
                $('#form-amount').attr('value', paymentAmount);

            });

        });


        $(document).on('submit', '#assignDistributorsForm', function (e) {
            e.preventDefault();

            var form = $(this);

            var doing = false;
            form.find('.btn-submit').val('Saving...');
         //   form.find('.btn-submit').attr('disabled', true);

            form.find('.has-error').removeClass('has-error');
            form.find('label.error').remove();
            form.find('.callout').remove();

            var formData = form.serialize();
            $amount = $("#removeWrapper").find('.amount').val();

            var formAction = form.attr('action');
            if (parseFloat($amount) != 0) {
                if (doing == false) {
                    doing = true;

                    $.ajax({
                        url: formAction,
                        type: 'POST',
                        data: formData,
                        dataType: 'json'
                    })
                        .done(function (response) {

                            if(response.data.message == 'has duplicate'){
                                toastr.error('Cannot assign same distributor multiple times!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            }else{
                                toastr.success('Distributor assigned successfully!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            }

                        })
                        .fail(function () {

                            toastr.error('Something Went Wrong!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        })
                        .always(function () {
                            doing = false;
                            form.find('.btn-submit').removeAttr('disabled');
                            form.find('.btn-submit').val('Save');
                            $("#assignDistributor").modal('hide');
                            $("#sendmoney-modal").modal('hide');
                            window.location.reload();
                        });
                }
            }
            else {
                toastr.error('Amount cannot be zero!', {
                    closeButton: true,
                    progressBar: true,
                });
            }

        });
        $(document).on('submit', '#agent-assign-form', function (e) {
            e.preventDefault();

            var form = $(this);

            var doing = false;
            form.find('.agent-assign-save').val('Sending...');
            form.find('.agent-assign-save').attr('disabled', true);

            form.find('.has-error').removeClass('has-error');
            form.find('label.error').remove();
            form.find('.callout').remove();

            var formData = form.serialize();


            var formAction = form.attr('action');

                if (doing == false) {
                    doing = true;

                    $.ajax({
                        url: formAction,
                        type: 'POST',
                        data: formData,
                        dataType: 'json'
                    })
                        .done(function (response) {
                            toastr.success('Agent assigned successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        })
                        .fail(function () {

                            toastr.error('Something Went Wrong!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        })
                        .always(function () {
                            doing = false;
                            form.find('.agent-assign-save').removeAttr('disabled');
                            form.find('.agent-assign-save').val('Save');
                            $("#sendmoney-modal").modal('hide');
                         //   window.location.reload();
                        });
                }


        });
        $(document).on('submit', '#change-status-modal-form', function (e) {
            e.preventDefault();

            var form = $(this);

            var doing = false;
            form.find('.status-change-save').val('Sending...');
            form.find('.status-change-save').attr('disabled', true);

            form.find('.has-error').removeClass('has-error');
            form.find('label.error').remove();
            form.find('.callout').remove();

            var formData = form.serialize();


            var formAction = form.attr('action');

                if (doing == false) {
                    doing = true;

                    $.ajax({
                        url: formAction,
                        type: 'POST',
                        data: formData,
                        dataType: 'json'
                    })
                        .done(function (response) {
                            toastr.success('Status changed successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        })
                        .fail(function () {

                            toastr.error('Something Went Wrong!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        })
                        .always(function () {
                            doing = false;
                            form.find('.status-change-save').removeAttr('disabled');
                            form.find('.status-change-save').val('Save');
                            $("#sendmoney-modal").modal('hide');
                            window.location.reload();
                        });
                }
       });

      $("#update_agent_rate_button").on("click",function(){
          var selectedData=[];
          $.each($('input.checks:checked'), function () {
              var id = $(this).val();
              selectedData.push(id);
          });

              var dataUrl = "{{route('updateAgentRate.modal')}}" + '?ids=' + selectedData;
              $("#update_agent_rate_button").attr('data-url', dataUrl);



      });

        $("#update_cost_rate_button").on("click",function(){
            var selectedData=[];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('updateCostRate.modal')}}" + '?ids=' + selectedData;
            $("#update_cost_rate_button").attr('data-url', dataUrl);



        });
      $("#update_order_status_button").on("click",function(){
          var selectedData=[];
          $.each($('input.checks:checked'), function () {
              var id = $(this).val();
              selectedData.push(id);
          });
              var dataUrl = "{{route('orderStatus.modal')}}" + '?ids=' + selectedData;
              $("#update_order_status_button").attr('data-url', dataUrl);
      });

        $("#distributor_assign_multiple_button").on("click",function(){
            var selectedData=[];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('multipleDistributorAssign.modal')}}" + '?ids=' + selectedData;
            $("#distributor_assign_multiple_button").attr('data-url', dataUrl);
        });
       function actionbox(value,txn_id,agentId,clientId,user){

           console.log(value,txn_id,agentId,clientId,user);
           if(value == 1){
            $("#comment-modal").modal('show');

               var modal = $('#comment-modal');


               if (clientId !='') {
                   $('#client-checkbox').css('display', 'block');
                   $('#agent-checkbox').css('display', 'none');
                   $("input[name='client_user_id']").attr('checked', 'checked');
                   $("input[name='agent_user_id']").removeAttr('checked');
                   modal.find('.modal-body #client_id_value').val(clientId);
                   $('#clientname').html(user);
                   modal.find('.modal-body #agent_id_value').val('');
                   $('#agentname').html('Agent');

               }
               if (agentId != '') {
                   $('#agent-checkbox').css('display', 'block');
                   $('#client-checkbox').css('display', 'none');
                   $("input[name='agent_user_id']").attr('checked', 'checked');
                   $("input[name='client_user_id']").removeAttr('checked');
                   modal.find('.modal-body #agent_id_value').val(agentId);
                   $('#agentname').html(user);
                   modal.find('.modal-body #client_id_value').val('');
                   $('#clientname').html('Client');
               }

               if (agentId == '' && clientId == '') {
                   $("input[name='client_user_id']").removeAttr('checked');
                   $("input[name='agent_user_id']").removeAttr('checked');
                   $('#agent-checkbox').css('display', 'none');
                   $('#client-checkbox').css('display', 'none');
                   modal.find('.modal-body #agent_id_value').val('');
                   $('#agentname').html('Agent');
                   modal.find('.modal-body #client_id_value').val('');
                   $('#clientname').html('Client');
               }
               $("#comment-modal #save-button").attr('data-transactionid',txn_id);
               loadAllComments(txn_id);
           }else if(value == 2){
              var url= AppUrl + '/transactions/show/'+txn_id;
               window.location= url;
           }else{
               var url= AppUrl + '/invoice/'+txn_id;
               window.location= url;

           }
         // console.log( $('#actionselect').find(":selected").attr('value'));
       }
    </script>
@stop