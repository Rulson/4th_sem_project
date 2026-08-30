
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Update Order Status</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => 'updateOrderStatus.store','id' => 'update-order-status-form','method' => 'post'))!!}

<div class="modal-body">

        <div class="form-group">

            <div class="form-group" >
                {{ Form::label('status_id', 'Status', ['class' => 'control-label']) }}
                {{ Form::select('status_id', transactionStatusList(), null, ['class' => 'form-control','id'=>'status']) }}
                {{ Form::hidden('ids', $ids) }}
            </div>
       </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary order-status-modal-update">Update</button>
</div>

