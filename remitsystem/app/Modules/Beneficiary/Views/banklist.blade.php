@extends('layouts.main')
@section('title', 'All Banks')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Banks</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Banks List
                <div class="pull-right">
                    <a href="{{ route('bank.create') }}" class="btn btn-primary btn-sm">Add
                        New Bank</a>
                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped table-bordered datatable table-sm" id="users">
                    <thead>
                    <tr>
                        <th> Id</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banks as $bank)

                        <tr>
                            <th>{{ $bank->id }}</th>
                            <td>{{$bank->name}}</td>
                            <td>
                                <a href="{{route('bank.edit', [$bank->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                                @if($bank->active == 1)
                                <a href="{{route('bank.change.status', [$bank->id,0])}}" data-toggle="tooltip" title="Deactivate Bank" data-placement="bottom" class="btn btn-sm btn-danger"><i
                                            class="fa fa-minus-circle"></i></a>
                                    @else
                                    <a href="{{route('bank.change.status', [$bank->id,1])}}" data-toggle="tooltip" title="Activate Bank" data-placement="bottom" class="btn btn-sm btn-success"><i
                                                class="fa fa-check-circle"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        $('#users').DataTable({
            order: [[0, 'desc']],
        });

    </script>

@stop




