
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Assign Agent</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => ['transactions.store.assign.agent',$transaction_id], 'id' => 'agent-assign-form','method' => 'post'))!!}
<div class="modal-body">

        <div class="form-group">
            <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
            <div class="form-group" >
                {{ Form::label('agent_id', 'Select Agent', ['class' => 'control-label']) }}
                @php
                    $agent_list = agentList();
        $agent_list[0]='Select Agent';
        ksort($agent_list);@endphp
                @if(isset($data))
                {{ Form::select('agent_id', $agent_list, $data->agents_id, ['class' => 'form-control','id'=>'distributor_id']) }}
           @else
                    {{ Form::select('agent_id', $agent_list, null, ['class' => 'form-control','id'=>'distributor_id']) }}

                @endif</div>   </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary agent-assign-save">Save</button>
</div>
{!!Form::close()!!}

