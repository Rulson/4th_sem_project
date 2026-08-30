<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>State/District</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                            {!!Form::label('parent_id', 'Parent (*)', array('class' => 'control-label')) !!}
                            {!!Form::select('parent_id',$data['state'], null, array('class' => 'form-control', 'id'=>'parent_id', 'placeholder'=>'Select parent'))!!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                            {!!Form::label('name', 'Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'placeholder'=>'Enter Name'))!!}
                            @if($errors->has('name'))
                                {!! $errors->first('name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('type')) {{'has-error'}} @endif">
                            {!!Form::label('type', 'Type (*)', array('class' => 'control-label')) !!}
                            {{ Form::select('type', ['au_state' => 'Australian State', 'np_state' => 'Nepali State', 'district' => 'District','aus_suburb'=>'Australian Suburb'], !empty($state) ? $state->type : null, ['id' => 'type','class'=>'form-control']) }}
                            @if($errors->has('last_name'))
                                {!! $errors->first('last_name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('postcode')) {{'has-error'}} @endif">
                            {!!Form::label('postcode', 'Postcode', array('class' => 'control-label')) !!}
                            {{ Form::text('postcode', null, ['id' => 'type','class'=>'form-control']) }}
                            @if($errors->has('last_name'))
                                {!! $errors->first('last_name', '<label class="control-label text-danger"
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


@section('page-script')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $('#parent_id').select2({
                theme: "bootstrap"
            });
        })
    </script>
@endsection
