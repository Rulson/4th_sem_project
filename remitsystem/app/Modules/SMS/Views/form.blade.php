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
        {!!Form::label('receiver', 'Select', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-6">
            {!!Form::select('receiver',getAgentAndSender(),null, array('class' => 'form-control', 'placeholder'=>'Choose Receiver','id'=>'receiverSelect'))!!}
            @if($errors->has('receiver'))
                {!! $errors->first('receiver', '<label class="control-label text-danger"
                                           for="inputError">:message</label>') !!}
            @endif
        </div>
    </div>
    <div id="senderList"  style="display:none;" class="form-group @if($errors->has('destination')) {{'has-error'}} @endif">
        {!!Form::label('destination', 'To', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-6">
            <select class="form-control" name="sender_person_id" id="destination" required>

                @foreach($data['senders'] as $value)
                    <option value="{{$value->personId}}" data-value="{{$value->phoneNumber}}">{{$value->fullName}}
                        | {{$value->phoneNumber}}
                    </option>
                @endforeach
            </select>
            @if($errors->has('destination'))
                {!! $errors->first('destination', '<label class="control-label text-danger"
                                           for="inputError">:message</label>') !!}
            @endif
        </div>
    </div>
   <div id="agentList" style="display:none;" class="form-group @if($errors->has('destination2')) {{'has-error'}} @endif">
        {!!Form::label('destination', 'To', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-md-6">
            <select class="form-control" name="agent_person_id" id="destination2" required>
                @foreach($data['agents'] as $value)
                    <option value="{{$value->personId}}" data-value="{{$value->phoneNumber}}">{{$value->fullName}}
                        | {{$value->phoneNumber}}
                    </option>
                @endforeach
            </select>
            @if($errors->has('destination2'))
                {!! $errors->first('destination', '<label class="control-label text-danger"
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


