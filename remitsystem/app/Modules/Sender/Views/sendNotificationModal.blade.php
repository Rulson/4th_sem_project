<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Push Notification</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route'=>['sender.send-notification'],'id'=>'email-send'))!!}
<input type="hidden" name="id" value="{{ $id }}">
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('title')) {{'is-invalid'}} @endif" id="street_div">
                            {!!Form::label('agent_id', 'Agent (*)', array('class' => 'control-label','id'=>'agent_id-label')) !!}
                            {{ Form::select('agent_id', $applications,null, ['id' => 'agent_id','class'=>'form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('title')) {{'is-invalid'}} @endif" id="street_div">
                            {!!Form::label('title', 'Title (*)', array('class' => 'control-label','id'=>'title-label')) !!}
                            {!!Form::text('title', null, array('class' => 'form-control', 'id'=>'title', 'placeholder'=>'Enter Title'))!!}
                        </div>
                        <div id="error-title">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('message')) {{'is-invalid'}} @endif" id="message_div">
                            {!!Form::label('message', 'Message (*)', array('class' => 'control-label', 'id'=>'message-label')) !!}
                            {!!Form::textarea('message', null, array('class' => 'form-control ckeditor', 'id'=>'message', 'placeholder'=>'Enter Messagee'))!!}
                        </div>
                        <div id="error-message"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('url')) {{'is-invalid'}} @endif" id="street_div">
                            {!!Form::label('url', 'Url', array('class' => 'control-label','id'=>'url-label')) !!}
                            {!!Form::text('url', null, array('class' => 'form-control', 'id'=>'url', 'placeholder'=>'Enter Url'))!!}
                        </div>
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
