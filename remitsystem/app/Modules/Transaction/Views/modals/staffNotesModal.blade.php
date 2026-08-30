
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Add / Update Payment Notes</h4>
</div>

{!!Form::open(array('route' => ['transaction.staffNotes.store',$transaction_id], 'id' => 'staff-notes','method' => 'post'))!!}
<div class="modal-body">
    @if(isset($transaction))
        <div class="form-group">
            {{ Form::label('staff_notes', 'Payment Notes', ['class' => 'control-label']) }}
            {{ Form::textarea('staff_notes',$transaction->staff_notes,['class' => 'form-control', 'placeholder' => 'Payment Notes']) }}
        </div>
    @else
        <div class="form-group">
            {{ Form::label('staff_notes', 'Payment Notes', ['class' => 'control-label']) }}
            {{ Form::textarea('staff_notes',null,['class' => 'form-control', 'placeholder' => 'Payment Notes']) }}
        </div>
    @endif

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save</button>
</div>
{!!Form::close()!!}

