
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Change Verified Status</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => 'changeIsVerified', 'id' => 'change-verified-status-modal-form','method' => 'post'))!!}
<div class="modal-body">

    <div class="form-group">
        <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
        <div class="form-group" >
            {{ Form::label('is_verified', 'Status', ['class' => 'control-label']) }}
            {{ Form::select('is_verified',[0=>'Unverified',1=>'Verified'], $is_verified, ['class' => 'form-control','id'=>'status']) }}
        </div>   </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary verified-status-change-save">Save</button>
</div>
{!!Form::close()!!}

