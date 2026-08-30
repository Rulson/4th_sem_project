
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Update Agent Rate</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => 'updateAgentRate.store','id' => 'agent-rate-store-form','method' => 'post'))!!}

<div class="modal-body">

        <div class="form-group">

            <div class="form-group" >
                {{ Form::label('agent_rate', 'Agent Rate', ['class' => 'control-label']) }}
                <input type="number" name="agent_rate" step="0.01" class="form-control" id="agent_rate">
              {{ Form::hidden('ids', $ids) }}
            </div>   </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary agent-rate-update">Update</button>
</div>
{!!Form::close()!!}

