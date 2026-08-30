<div class="modal-header">
    <h4 class="modal-title">Change Beneficiary</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<form method="post" action="{{route('store.beneficiary.change',$transaction_id)}}">

{{csrf_field()}}
<div class="modal-body">
       <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>Select Beneficiary (*)</label>


                                <select name="beneficiary_id" class="form-control">

                                @foreach($beneficiary as $key=>$value)
                                        <option value="{{$value['beneficiary_id']}}" {{($value['beneficiary_id']==$beneficiaryId)? 'selected':''}}>{{$value['full_name']}}</option>
                              @endforeach  </select>

                                    @if($errors->has('beneficiary'))
                                    {!! $errors->first('beneficiary', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
   </div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
