@extends('layouts.main')
@section('title', 'All Distributors')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item ">Manage Applications</li>
    <li  class="breadcrumb-item active">All Applications</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Application List
                <div class="pull-right">
                    <a href="{{ route('application.create') }}" class="btn btn-primary btn-sm">Add
                        New Application</a>
                </div>
            </div>
            <div class="card-body">
                <table id="exampleDistributor" class="table table-bordered table-striped datatable">
                    <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Agent</th>
                        <th>Package Name</th>
                        <th>Publish</th>
                        <th>Logo</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i = 1)
                    @foreach($datas as $data)
                    <tr>
                        <th>{{$i++}}</th>
                        <td>{{$data->name}}</td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->phone_number}}</td>
                        <td>{{ ($data->agent_id) ? getAgentNameByUserId($data->agent_id) : ''}}</td>
                        <td>{{$data->package_name}}</td>
                        <td>{{ ($data->published == 1) ? 'Yes' : 'No'}}</td>
                        <td><img src="{{ url('/application/'.$data->logo) }}" height="50px" width="50px"></td>
                        <td>
                            <a href="{{route('application.show',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="View" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>

                            <a href="{{route('application.edit', [$data->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                    class="fa fa-edit"></i></a>
                            <a href="{{route('application.delete', [$data->id])}}" data-toggle="tooltip" title="Delete" data-placement="bottom" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure ,you want to delete this application?')"><i
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
@section('page-script')
<script>
    $('#exampleDistributor').DataTable( {
        language: {
             zeroRecords: "No records found",
        },
        'scrollX': true,
        "pageLength": 50,
        dom: 'lBfrtip',
        autoWidth:false,
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            'copy', 'csv', 'excel', 'print'
        ],
    });
</script>
@endsection
