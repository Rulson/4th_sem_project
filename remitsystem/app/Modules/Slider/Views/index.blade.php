@extends('layouts.main')
@section('title', 'All Sliders')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Sliders</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Sliders
                <div class="pull-right">
                    <a href="{{ route('slider.create') }}" class="btn btn-primary btn-sm">Add New Slider</a>
                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped table-bordered datatable table-sm" id="users">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Image</th>
                        <th>Sort Order</th>
                        <th>Application</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($sliders as $slider)

                        <tr>
                            <th>{{ $i++ }}</th>
                            <td>{{$slider->name}}</td>
                            <td>{{$slider->url}}</td>
                            <td><img src="{{ asset('sliders/'.$slider->image) }}" height="50" width="90"></td>
                            <td>{{$slider->sort_order}}</td>
                            <td>{{getApplicationDetail('id',$slider->application_id)->name}}</td>
                            <td>
                                <a href="{{route('slider.edit', [$slider->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                                <a href="{{ route('slider.delete', [$slider->id]) }}" data-toggle="tooltip" data-placement="bottom" title="Delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure ,you want to delete this slider?')"><i
                                            class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





