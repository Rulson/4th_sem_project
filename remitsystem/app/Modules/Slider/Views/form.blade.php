<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Slider</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('first_name')) {{'has-error'}} @endif">
                            {!!Form::label('name', 'Name (*)', array('class' => 'control-label')) !!}
                            {!!Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'placeholder'=>'Enter name'))!!}
                            @if($errors->has('name'))
                                {!! $errors->first('name', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!!Form::label('application_id', 'Application (*):', array('class' => 'control-label')) !!}

                                {{ Form::select('application_id',getApplicationList(),  null, ['id' => 'application_id','class'=>'form-control','placeholder'=>'Select Application']) }}
                                @if($errors->has('application_id'))
                                    {!! $errors->first('application_id', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('url')) {{'has-error'}} @endif">
                            {!!Form::label('url', 'URL', array('class' => 'control-label')) !!}
                            {!!Form::text('url', null, array('class' => 'form-control', 'id'=>'url', 'placeholder'=>'Enter URL'))!!}
                            @if($errors->has('url'))
                                {!! $errors->first('url', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('sort_order')) {{'has-error'}} @endif">
                            {!!Form::label('sort_order', 'Sort Order (*)', array('class' => 'control-label')) !!}
                            {{ Form::number('sort_order',null, ['id' => 'sort_order','class'=>'form-control','min'=>1]) }}
                            @if($errors->has('sort_order'))
                                {!! $errors->first('sort_order', '<label class="control-label text-danger"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($slider) && $slider->image != '')
                    <img src="{{ asset('sliders/'.$slider->image) }}" height="50px" width="50px">
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('sort_order')) {{'has-error'}} @endif">
                            {!!Form::label('image', 'Image (*)', array('class' => 'control-label')) !!}
                            <input type="file" name="slider_image" class="form-control"
                                   accept="image/jpeg , image/jpg, image/gif, image/png">
                            @if($errors->has('sort_order'))
                                {!! $errors->first('sort_order', '<label class="control-label text-danger"
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
@endsection