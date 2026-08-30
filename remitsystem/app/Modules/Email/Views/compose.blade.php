@extends('layouts.main')
@section('title', 'Email')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Email</li>
    <li  class="breadcrumb-item active">Compose Message</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Compose Email Message

            </div>
            <!-- /.box-header -->
            {!!Form::open( array('route' => ['email.send'],'method'=>'POST','class' => 'form-horizontal form-left','id'=>'email-submit'))!!}

            <div class="card-body">

                        <div class="form-row mb-3">
                            <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                            <div class="col-10 col-sm-11">
                                <input type="email" class="form-control" name="email" id="to" placeholder="Type email">
                                @if($errors->has('email'))
                                    {!! $errors->first('email', '<label class="control-label text-danger"
                                                               for="inputError">:message</label>') !!}
                                @endif
                            </div>

                        </div>
                        <div class="form-row mb-3">
                            <label for="cc" class="col-2 col-sm-1 col-form-label">Subject:</label>
                            <div class="col-10 col-sm-11">
                                <input type="text" class="form-control" id="cc" name="subject" placeholder="Subject">
                                @if($errors->has('subject'))
                                    {!! $errors->first('subject', '<label class="control-label text-danger"
                                                                           for="inputError">:message</label>') !!}
                                @endif
                            </div>

                        </div>
                <div class="form-row mb-3">
                    <label for="cc" class="col-2 col-sm-1 col-form-label">Message:</label>
                    <div class="col-10 col-sm-11">
                        <textarea name="message" id="editor1"></textarea>
                        @if($errors->has('message'))
                            {!! $errors->first('message', '<label class="control-label text-danger"
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
            filebrowserUploadUrl: "{{route('ckeditor.upload.email', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            toolbar: [
                { name: 'document', items: [ 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
                [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
                '/',																					// Line break - next group will be placed in new line.
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'insert', items: [ 'Image'] },
            ]
        } );
       </script>
@endsection

