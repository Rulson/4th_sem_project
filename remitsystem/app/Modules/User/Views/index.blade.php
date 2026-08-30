@extends('layouts.main')
@section('title', 'All Users')
@section('breadcrumb')
    @parent
<li  class="breadcrumb-item active">All Agents</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                User List
                <div class="pull-right">
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Add
                        New User</a>
                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped table-bordered datatable" id="users">
                    <thead>
                    <tr>
                        <th>User Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)

                        <tr>
                            <th>{{ format_id($user->user_id, 'U') }}</th>
                            <td>{{$user->full_name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->number}}</td>
                            <td>{{$user->role}}</td>
                            <td>{{$user->status}}</td>
                                <td><a href="{{route('user.getPasswordReset',[$user->user_id])}}" data-toggle="tooltip" data-placement="bottom" title="Update Password" class="btn btn-sm btn-primary"><i class="fa fa-lock"></i></a>
                                    <a href="{{route('user.edit', [$user->user_id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <?php $icon = $user->user_status_id == 2 ? 'fa-minus-circle' : 'fa-check-circle'; ?>
                                    <a data-toggle="tooltip" title="Change Status" class="btn btn-sm btn-danger" data-placement="bottom" href="{{route('user.changeStatus', [$user->user_id])}}" onclick="return confirm('Are you sure?')"><i class="fa {{$icon}}"></i></a>
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
