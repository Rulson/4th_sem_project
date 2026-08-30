
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Update Cost Rate</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
{!!Form::open(array('route' => 'updateCostRate.store','id' => 'cost-rate-store-form','method' => 'post'))!!}

<div class="modal-body">

        <div class="form-group">
            <div class="form-group" >
                {{ Form::label('cost_rate', 'Cost Rate', ['class' => 'control-label']) }}
                <input type="number" name="cost_rate" step="0.01" class="form-control" id="cost_rate">
              {{ Form::hidden('ids', $ids) }}
            </div>
        </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary agent-rate-update">Update</button>
</div>
{!!Form::close()!!}

