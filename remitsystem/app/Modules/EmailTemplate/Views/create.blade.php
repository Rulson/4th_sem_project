@extends('layouts.main')
@section('title', 'Add Email Template')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Email Template</li>
    <li  class="breadcrumb-item active">Create</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Create Email Template

            </div>
            <!-- /.box-header -->
            {!!Form::open( array('route'=>'email-template.store','method'=>'POST','class' => 'form-horizontal form-left'))!!}

            <div class="card-body">

                @include('EmailTemplate::form')

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

