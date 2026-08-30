
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Assign Distributor</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!!Form::open(array('route' => ['assignMultipleDistributor.store'], 'id' => 'distributorMultipleAssign-form','method' => 'post'))!!}
<div class="modal-body">

        <div class="form-group">
            <div class="form-group" >
                {{ Form::label('distributor_id', 'Select Distributor', ['class' => 'control-label']) }}
                @php
                    $distributor_list = distributorList();
               /* $distributor_list[0]='Select Distributor';
                ksort($distributor_list);*/ @endphp

                {{ Form::select('distributor_id', $distributor_list, null, ['class' => 'form-control','id'=>'distributor_id']) }}
            </div>
            {{ Form::hidden('ids', $ids) }}
        </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary multiple-distributor-assign-save">Save</button>
</div>
{!!Form::close()!!}

