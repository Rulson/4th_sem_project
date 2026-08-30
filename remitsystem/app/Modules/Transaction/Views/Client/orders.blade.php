@extends('layouts.main')
@section('title', 'All Orders')
@section('breadcrumb')
    @parent
        <li class="breadcrumb-item active">All Orders</li>
        <a href="{{ url('send-money') }}" class="btn btn-primary btn-sm ml-5"><i class="fa fa-plus"></i> Add New Order</a>
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

    </style>
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">


                    <div class="pull-right">



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

                    </div>

            </div>

            <div class="card-body" style="overflow-x: scroll;" id="orders-list">
            </div>

        </div>
    </div>

    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @include('Transaction::modals/Client/addCommentModal',['transaction_id'=>34])
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


        $(document).ready(function () {
            getOrders("{{$pgNum}}");
            $('#filterList').on('keyup', function () {
                searchTerm = $(this).val();
                getOrders();
            });


        });




        function actionbox(value, txn_id ,user) {


            if (value == 1) {
                $("#comment-modal").modal('show');
                $("#comment-modal #save-button").attr('data-transactionid', txn_id);
                loadAllComments(txn_id);
            } else if (value == 2) {
                var url = AppUrl + '/transactions/show/' + txn_id;
                window.location = url;
            } else {
                var url = AppUrl + '/invoice/' + txn_id;
                window.location = url;

            }
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

    </script>
@stop
