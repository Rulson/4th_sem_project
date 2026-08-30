<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Send Email</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route'=>['sender.send-email'],'id'=>'email-send'))!!}
<input type="hidden" name="sender_id" value="{{ $sender->id }}">
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('subject')) {{'is-invalid'}} @endif" id="street_div">
                            {!!Form::label('subject', 'Subject (*)', array('class' => 'control-label','id'=>'subject-label')) !!}
                            {!!Form::text('subject', null, array('class' => 'form-control', 'id'=>'subject', 'placeholder'=>'Enter Subject'))!!}
                        </div>
                        <div id="error-subject">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('message')) {{'is-invalid'}} @endif" id="message_div">
                            {!!Form::label('message', 'Message (*)', array('class' => 'control-label', 'id'=>'message-label')) !!}
                            {!!Form::textarea('message', null, array('class' => 'form-control ckeditor', 'id'=>'message', 'placeholder'=>'Enter Messagee'))!!}
                        </div>
                        <div id="error-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" id="email-sendPopUp" class="btn btn-primary">Send</button>
</div>
<script src="{{asset('assets/coreUI/js/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.replace('message', {
        height: 250
    });
</script>
