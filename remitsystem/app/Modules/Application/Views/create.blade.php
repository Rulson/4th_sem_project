@extends('layouts.main')
@section('title', 'Add Application')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{route('applications.index')}}" title="All Applications">Applications</a></li>
    <li class="breadcrumb-item active">Add</li>
@stop

@section('content')

    {!!Form::open(array('route' => ['application.store'],'files'=>'true'))!!}

    <!-- /.box-header -->
                <div class="animated fadeIn">
                    @include('Application::form')
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Save</button>
                    </div>
                </div>
            {!!Form::close()!!}
@endsection
@section('page-script')

@endsection
