
    <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Compose Email Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{route('email.order.send')}}" method="POST" class="form-horizontal" id="sendEmail-form">
        {{csrf_field()}}
        <div class="modal-body">
	    <input type="hidden" class="form-control" name="order_id" value="{{$ids}}">
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="cc" name="subject" placeholder="Subject">
                    @if($errors->has('subject'))
                        {!! $errors->first('subject', '<label class="control-label text-danger"
                                                            for="inputError">:message</label>') !!}
                    @endif
                </div>

            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">Message:</label>
                <div class="col-10 col-sm-11">
                    <textarea name="message" id="editor1"></textarea>
                    @if($errors->has('message'))
                        {!! $errors->first('message', '<label class="control-label text-danger"
                                                            for="inputError">:message</label>') !!}
                    @endif
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="submit_distributor_assign" class="btn btn-primary btn-submit" >Send</button>
        </div>
    </form>

<script>
    CKEDITOR.replace( 'editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload.email', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        toolbar: [
            { name: 'document', items: [ 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
            [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
            '/',																					// Line break - next group will be placed in new line.
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'insert', items: [ 'Image'] },
        ]
    } );
    </script>

