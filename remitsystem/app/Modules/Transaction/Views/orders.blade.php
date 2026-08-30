@extends('layouts.main')
@section('title', 'All Orders')
@section('breadcrumb')
    @parent
        <li class="breadcrumb-item active">All Orders</li>
        @if(!in_array(Auth::user()->level_id, [6,7,8]))<a href="{{ url('send-money') }}" class="btn btn-primary btn-sm ml-5"><i class="fa fa-plus"></i> Add New Order</a>@endif
        @if(Auth::user()->level_id != 5)<div id="sumStatus" class="pull-right ml-5" style="margin-top: -2px;"></div>@endif
        <img class="loading pull-right ml-1 ml-5 hide" src="{{asset('assets/img/loader.gif')}}">
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
        .cursor-pointer {
            cursor: pointer;
        }

    </style>
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                @if(in_array(Auth::user()->level_id, [1,2,5,6,8]))

                    <div class="pull-left">
                        @if(!in_array(Auth::user()->level_id,[6,5]))
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                           data-url="" class="btn btn-sm btn-success" id="update_agent_rate_button">Update Agent Rate</a>
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                           data-url="" class="btn btn-sm btn-success" id="update_order_status_button">Update Order
                            Status</a>
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                           data-keyboard="false"
                           data-url="" class="btn btn-sm btn-success" id="update_cost_rate_button"
                           style="">Update Cost Rate</a>
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                           data-keyboard="false"
                           data-url="" class="btn btn-sm btn-success" id="distributor_assign_multiple_button"
                           style="">Distributor Assign</a>
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                           data-keyboard="false"
                           data-url="" class="btn btn-sm btn-success" id="send_email_button"
                           style="">Send Mail</a>
                        @endif
                    </div>

                @endif

                    <div class="pull-right">
                        @if(in_array(Auth::user()->level_id, [1,2,7,8]))
                        <div class="btn-group pull-right ml-1">
                            <div class="dropdown show">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                     x-placement="bottom-start"
                                     style="position: absolute; transform: translate3d(0px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" id="excel-export1"><b>Export 1</b></a>
                                    <a class="dropdown-item" href="#" id="excel-export2"><b>Export 2</b></a>
{{--                                    <a class="dropdown-item" href="#" id="excel-export3"><b>Export 3</b></a>--}}
{{--                                    <a class="dropdown-item" href="#" id="excel-export4"><b>Export 4</b></a>--}}
{{--                                    <a class="dropdown-item" href="#" id="excel-export5"><b>Export 5</b></a>--}}
{{--                                    <a class="dropdown-item" href="#" id="excel-export6"><b>Export 6</b></a>--}}
{{--                                    <a class="dropdown-item" href="#" id="excel-export7"><b>Export 7</b></a>--}}
{{--                                    <a class="dropdown-item" href="#" id="excel-export8"><b>Export 8</b></a>--}}
                                    <a class="dropdown-item" href="#" id="austrac-export"><b>Austrac Export</b></a>
                                </div>
                            </div>

                        </div>
                        @endif


                    <div class="btn-group pull-right">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" id="paginateButton" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$num}}</button>
                            <div class="dropdown-menu" aria-labelledby="paginateButton">
                                <a class="dropdown-item" href="#" onclick="pagecount(10)">10</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(50)">50</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(100)">100</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(300)">300</a>
                                <a class="dropdown-item" href="#" onclick="pagecount(500)">500</a>
                            </div>
                        </div>
                    </div>
                    <input id="filterList" placeholder="Search" class="pull-right mr-1" style="margin-top:-4px;height: 28px;">
                    @if(Auth::user()->level_id != 5)
                        <div class="btn-group pull-right mr-1">
                            <div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" id="statusButton" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All
                                </button>
                                <div class="dropdown-menu" aria-labelledby="paginateButton">
                                    <a class="dropdown-item" href="{{route('transactions.orders')}}">All</a>

                                    @foreach($status_list as $status_name=>$status_id)
                                        <a class="dropdown-item" href="#"
                                             onclick="getSumStatus({{$status_id}})">{{$status_name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <a href="{{route('transactions.search.view')}}" class="btn btn-warning btn-sm pull-right mr-1">Advanced Search</a>
                    @endif
                    </div>
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)

                @endif
            </div>

            <div class="card-body" style="overflow-x: scroll;" id="orders-list">
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
    <div class="modal fade sendingmodal" id="sendmoney-modal" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
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
    <script type="text/javascript">
        var AppUrl = "{{url('/')}}";
        var total_amount;
        var recCount = "{{$num}}";
        var searchTerm = "{{$search}}";
        var filteredStatus = "{{$getStatus}}";


        var transactionStatusId = <?php echo json_encode($transaction_status_id); ?>;


        var addBy = "{{$added_by}}";
        var from = "{{$from}}";
        var toDat = "{{$to}}";
        var distributrId = "{{$distributor_id}}";
        var sendingAmnt = "{{$sending_amount}}";
        var senderName = "{{$senderName}}";
        var beneficiaryName = "{{$beneficiaryName}}";
        var paymentType = "{{$paymentType}}";
        var id_to = "{{ $id_to }}";
        var id_from = "{{ $id_from }}";
        var xhr = null;

        // console.log(transactionStatusId,addBy,from,distributrId,sendingAmnt,senderName,beneficiaryName);


        function pagecount(count) {
            recCount = count;
            $('#paginateButton').text(count);
            getOrders();
        }

        function getOrders(pgNum = 1) {
            if(xhr){
                xhr.hasAborted = true;
                xhr.abort();
            }

            $('.loading').show();
            xhr = $.ajax({
                url: "{{route('transactions.getOrders')}}" + '?status=' + filteredStatus + '&num=' + recCount + '&search=' + searchTerm + '&page=' + pgNum + '&transactionStatusId=' + transactionStatusId + '&addedBy=' + addBy + '&from=' + from + '&to=' + toDat + '&distributorId=' + distributrId + '&sendingAmnt=' + sendingAmnt + '&senderName=' + senderName + '&beneficiaryName=' + beneficiaryName + '&payment_type=' + paymentType + '&id_to=' + id_to + '&id_from='+id_from,
                type: 'GET',
            })
                .done(function (response) {
                    $('#orders-list').html(response.html);
                    $('#statusButton').html(response.status_name);
                    $('#sumStatus').html("<div class=\"badge badge-info btn-sm p-2 mr-1\">AUD " + response.orders_aud + "</div><button class=\"badge badge-info btn-sm p-2\">NPR " + response.orders_npr + "</button>");
                    $('.loading').hide();
                //    console.log(response);
                })
                .fail(function () {
                    if (!xhr.hasAborted) {
                        toastr.error('Something Went Wrong!', {
                            closeButton: true,
                            progressBar: true,
                        });
                        $('.loading').hide();
                    }
                })
                .always(function () {
                    doing = false;
                });
        }
        function getSumStatus(statusId){
            filteredStatus = statusId;
            getOrders();
        }

     /*   function getStatusSum(statusId) {

            $.ajax({
                url: "{{route('transactions.getStatusSum')}}" + '?status=' + statusId,
                type: 'GET',
            })
                .done(function (response) {
                    $('#statusButton').html(response.data.status_name);
                    $('#sumStatus').html("<button class=\"btn btn-info btn-sm\">AUD " + response.data.sum_aud + "</button><button class=\"btn btn-info btn-sm\">NPR " + response.data.sum_npr + "</button>");
                    filteredStatus = response.data.filteredStatus;
                    getOrders();

                })
                .fail(function () {
                    toastr.error('Something Went Wrong!', {
                        closeButton: true,
                        progressBar: true,
                    });
                })
                .always(function () {
                    doing = false;
                });
        }
*/
        $(document).ready(function () {
            getOrders("{{$pgNum}}");
            $('#filterList').on('keyup', function () {
                searchTerm = $(this).val();
                getOrders();
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
                    .done(function(response) {
                        if ( !response.success) {
                            toastr.error(response.data.message, {
                                closeButton: true,
                                progressBar: true,
                            });
                        } else {
                            toastr.success('Distributor assigned successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                    })
                    .fail(function (response) {
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
                        getOrders("{{$pgNum}}");
                        //window.location.reload();
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
                        getOrders("{{$pgNum}}");
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
                        // window.location.reload();
                        getOrders();
                    });
            }
        });

        $(document).on('submit', '#agent-rate-store-form', function (e) {
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
            // console.log(formData, formAction);

            if (doing == false) {
                doing = true;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                    .done(function (response) {
                        if (response.data.message == 'no transaction selected') {
                            toastr.error('No Transaction Selected!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == 'error') {
                            toastr.error('Cannot update. No agent were assigned to this transaction!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == '1') {
                            toastr.success('Agent Rate has been updated successfully!', {
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
                        /* form.find('.status-change-save').removeAttr('disabled');
                         form.find('.status-change-save').val('Save');*/
                        $("#sendmoney-modal").modal('hide');
                        // window.location.reload();
                        getOrders();
                    });
            }
        });
        $(document).on('submit', '#save-edit-transaction-form', function (e) {
           // e.preventDefault();
          //  var doing = false;
            var formData = new FormData($(this)[0]);
            var url = $(this).attr('action');
            // console.log(formData,url);
            $(this).find('.has-error').removeClass('has-error');
            $(this).find('label.error').remove();
            $(this).find('.callout').remove();

// process the form
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                encode: true
            })    .done(function (result) {

                if (result.data.message == '1') {
                    $('.modal').modal('hide');
                    getOrders();
                    toastr.success('Transaction has been updated successfully!', {
                        closeButton: true,
                        progressBar: true,
                    });
                       }
                if (result.data.message == '2') {
                    $('.modal').modal('hide');
                    toastr.error('Oops! Something went wrong!', {
                        closeButton: true,
                        progressBar: true,
                    });
                }
                setTimeout(function () {
                    $('.callout').remove()
                }, 2500);
            });
            event.preventDefault();


        });
            $(document).on('submit', '#update-order-status-form', function (e) {
            e.preventDefault();

            var form = $(this);

            var doing = false;
            form.find('.order-status-modal-update').val('Updating...');
            form.find('.order-status-modal-update').attr('disabled', true);

            form.find('.has-error').removeClass('has-error');
            form.find('label.error').remove();
            form.find('.callout').remove();

            var formData = form.serialize();
            var formAction = form.attr('action');
            // console.log(formData, formAction);

            if (doing == false) {
                doing = true;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                    .done(function (response) {
                        if (response.data.message == 'error') {
                            toastr.error('No Transaction Selected!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == 'success') {
                            toastr.success('Status has been updated successfully!', {
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
                         form.find('.order-status-modal-update').removeAttr('disabled');
                         form.find('.order-status-modal-update').val('Save');
                        $("#sendmoney-modal").modal('hide');
                        // window.location.reload();
                        getOrders();
                    });
            }
        });
        $(document).on('submit', '#cost-rate-store-form', function (e) {
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
            // console.log(formData, formAction);

            if (doing == false) {
                doing = true;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                    .done(function (response) {
                        if (response.data.message == 'error') {
                            toastr.error('No Transaction Selected!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == 'success') {
                            toastr.success('Cost Rate has been updated successfully!', {
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
                        /* form.find('.status-change-save').removeAttr('disabled');
                         form.find('.status-change-save').val('Save');*/
                        $("#sendmoney-modal").modal('hide');
                        // window.location.reload();
                        getOrders();
                    });
            }
        });
        $(document).on('submit', '#distributorMultipleAssign-form', function (e) {
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
            // console.log(formData, formAction);

            if (doing == false) {
                doing = true;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                    .done(function (response) {
                        console.log(response)
                        if (response.data.message == 'error') {
                            toastr.error('No Transaction Selected!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == 'success') {
                            toastr.success('Distributor has been assign successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }

                    })
                    .fail(function (response) {
                        console.log(response);
                        toastr.error('Something Went Wrong!', {
                            closeButton: true,
                            progressBar: true,
                        });
                    })
                    .always(function () {
                        doing = false;
                        /* form.find('.status-change-save').removeAttr('disabled');
                         form.find('.status-change-save').val('Save');*/
                        $("#sendmoney-modal").modal('hide');
                        // window.location.reload();
                        getOrders();
                    });
            }
        });

        $(document).on('submit', '#sendEmail-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var formAction = form.attr('action');
            // console.log(formData, formAction);

            if (doing == false) {
                doing = true;

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                    .done(function (response) {
                        if (response.data.message == 'error') {
                            toastr.error('No Transaction Selected!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                        if (response.data.message == 'success') {
                            toastr.success('Email has been scheduled successfully!', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }

                    })
                    .fail(function (response) {
                        console.log(response);
                        toastr.error('Something Went Wrong!', {
                            closeButton: true,
                            progressBar: true,
                        });
                    })
                    .always(function () {
                        doing = false;
                        /* form.find('.status-change-save').removeAttr('disabled');
                         form.find('.status-change-save').val('Save');*/
                        $("#sendmoney-modal").modal('hide');
                        // window.location.reload();
                        getOrders();
                    });
            }
        });

        $("#update_agent_rate_button").on("click", function () {
            var selectedData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('updateAgentRate.modal')}}" + '?ids=' + selectedData;
            $("#update_agent_rate_button").attr('data-url', dataUrl);


        });

        $("#update_cost_rate_button").on("click", function () {
            var selectedData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('updateCostRate.modal')}}" + '?ids=' + selectedData;
            $("#update_cost_rate_button").attr('data-url', dataUrl);


        });
        $("#update_order_status_button").on("click", function () {
            var selectedData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });
            var dataUrl = "{{route('orderStatus.modal')}}" + '?ids=' + selectedData;
            $("#update_order_status_button").attr('data-url', dataUrl);
        });

        $("#distributor_assign_multiple_button").on("click", function () {
            var selectedData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('multipleDistributorAssign.modal')}}" + '?ids=' + selectedData;
            $("#distributor_assign_multiple_button").attr('data-url', dataUrl);
        });

        $("#send_email_button").on("click", function () {
            var selectedData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                selectedData.push(id);
            });

            var dataUrl = "{{route('email.order.modal')}}" + '?ids=' + selectedData;
            $("#send_email_button").attr('data-url', dataUrl);
        });

        function actionbox(value, txn_id, agentId, clientId, user) {

             console.log(value, txn_id, agentId, clientId, user);
            if (value == 1) {
                $("#comment-modal").modal('show');

                var modal = $('#comment-modal');


                if (clientId != '') {
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
                    //alert(user)
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
                $("#comment-modal #save-button").attr('data-transactionid', txn_id);
                loadAllComments(txn_id);
            } else if (value == 2) {
                var url = AppUrl + '/transactions/show/' + txn_id;
                window.location = url;
            } else {
                var url = AppUrl + '/invoice/' + txn_id;
                window.location = url;

            }
            // console.log( $('#actionselect').find(":selected").attr('value'));
        }
        $('#TransactionReceiptImage').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var imgSrc = button.data('img');
            if (imgSrc == '') {
                $("#noReceipt").html('Image not supported ,please download it');
            }
            var modal = $(this);

            modal.find('.modal-body #imgSrc').attr('src', imgSrc);

        });

        $(document).on('submit', '#change-verified-status-modal-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var doing = false;
            form.find('.verified-status-change-save').val('Submitting...');
            form.find('.verified-status-change-save').attr('disabled', true);
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
                        toastr.success('Verified Status changed successfully!', {
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
                        form.find('.verified-status-change-save').removeAttr('disabled');
                        form.find('.verified-status-change-save').val('Save');
                        $("#sendmoney-modal").modal('hide');
                        getOrders();
                    });
            }
        });
    </script>
@stop
