<div class="modal-header">
    <h4 class="modal-title">Change Account</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<form method="post" action="{{route('store.account.change',$beneficiary_id)}}" id="update-account-form">
    {{csrf_field()}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <label>Select Account (*)</label>


                                    <select name="beneficiary_bank_details_id" class="form-control">

                                       @foreach($allAccounts as $key=>$value)
                                           <option value="{{$value['bankDetailId']}}" {{($value['bankDetailId'] == $activeAccount->bankDetailId)? 'selected':''}}>Account Name : {{$value['accountName']}} | Account No : {{$value['accountNo']}} | Branch : {{$value['bsb']}}  | Bank : {{$value['bankName']}}</option>
                                        @endforeach </select>

                                    @if($errors->has('beneficiary_bank_details_id'))
                                        {!! $errors->first('beneficiary_bank_details_id', '<label class="control-label text-danger"
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
        <button type="submit"  class="btn btn-primary">Update</button>
    </div>
</form>
