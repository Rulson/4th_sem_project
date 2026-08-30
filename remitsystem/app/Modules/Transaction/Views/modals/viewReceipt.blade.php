
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">View Receipt</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <div class="modal-body">
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

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<script>
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
