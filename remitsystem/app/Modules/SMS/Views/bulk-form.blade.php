<div class="card-body">
    <div class="form-group @if($errors->has('source')) {{'has-error'}} @endif">
        {!!Form::label('source', 'From', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-6">
            {!!Form::text('source',$number, array('class' => 'form-control', 'id'=>'source'))!!}
            @if($errors->has('source'))
                {!! $errors->first('source', '<label class="control-label text-danger"
                                                   for="inputError">:message</label>') !!}
            @endif
        </div>
    </div>

    <div class="form-group @if($errors->has('receiver')) {{'has-error'}} @endif">
        {!!Form::label('receiver', 'To:', array('class' => 'col-2 col-sm-1 col-form-label')) !!}
        <div class="col-md-6">
            {!!Form::select('receiver',getAgentAndSender(),null, array('class' => 'form-control', 'placeholder'=>'Choose Recipient'))!!}
            @if($errors->has('receiver'))
                {!! $errors->first('receiver', '<label class="control-label text-danger"
                                           for="inputError">:message</label>') !!}
            @endif
        </div>
    </div>


    <div class="form-group sms_div  @if($errors->has('sms')) {{'has-error'}} @endif">
        {!!Form::label('sms', 'Message ', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-6">
            {!!Form::textArea('sms', null, array('class' => 'form-control', 'id'=>'sms'))!!}
            @if($errors->has('sms'))
                {!! $errors->first('sms', '<label class="control-label text-danger"
                                                    for="inputError">:message</label>') !!}
            @endif
            <span id="error-message" style="color: red;"></span><br>
            <b><span id="message">0</span> characters. Cost: <span id="cost">1</span> SMS credit per recipient.</b>

        </div>
    </div>
</div>


