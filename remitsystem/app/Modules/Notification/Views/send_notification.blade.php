@extends('layouts.main')
@section('title', 'Send Notification')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Notification</li>
    <li  class="breadcrumb-item active">Compose Message</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Compose Notification Message
            </div>
            <!-- /.box-header -->
            {!!Form::open( array('route' => ['notification.send'],'method'=>'POST','class' => 'form-horizontal form-left','id'=>'email-submit'))!!}

            <div class="card-body">
                <div class="form-row mb-3">
                    <label for="to" class="col-2 col-sm-1 col-form-label">Application</label>
                    <div class="col-10 col-sm-11">
                        {{ Form::select('application_id', $applications,null, ['id' => 'application_id','class'=>'form-control','placeholder'=>'All']) }}
                        @if($errors->has('application_id'))
                            {!! $errors->first('application_id', '<label class="control-label text-danger"
                                                       for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <div class="form-row mb-3">
                    <label for="to" class="col-2 col-sm-1 col-form-label">Title:</label>
                    <div class="col-10 col-sm-11">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Type title" required>
                        @if($errors->has('title'))
                            {!! $errors->first('title', '<label class="control-label text-danger"
                                                       for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                
                
                <div class="form-row mb-3">
                    <label for="cc" class="col-2 col-sm-1 col-form-label">Message:</label>
                    <div class="col-10 col-sm-11">
                        <textarea name="message" required style="width:100%;height:100px;" placeholder="Enter your message to send to user here"></textarea>
                        @if($errors->has('message'))
                            {!! $errors->first('message', '<label class="control-label text-danger"
                                                                   for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>

                <div class="form-row mb-3">
                    <label for="to" class="col-2 col-sm-1 col-form-label">URL:</label>
                    <div class="col-10 col-sm-11">
                        <input type="text" class="form-control" name="url" id="url" placeholder="Type url">
                        @if($errors->has('url'))
                            {!! $errors->first('url', '<label class="control-label text-danger"
                                                       for="inputError">:message</label>') !!}
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-success pull-right mb-3">Send</button>
            </div>
           {!!Form::close()!!}
        </div>
    </div>






@endsection
@section('page-script')
    <script>
        CKEDITOR.replace( 'editor1', {
            toolbar: [
                { name: 'document', items: [ 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
                [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
                '/',																					// Line break - next group will be placed in new line.
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            ]
        }  );
       </script>
@endsection

