@extends('layouts.main')
@section('title', 'All Distributors')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item ">Manage Distributors</li>
    <li  class="breadcrumb-item active">All Distributors</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Distributor List
                @if(Auth::user()->level_id != 6)
                <div class="pull-right">
                    <a href="{{ route('distributor.create') }}" class="btn btn-primary btn-sm">Add
                        New Distributor</a>
                </div>
                    @endif
            </div>
            <div class="card-body">
                <table id="exampleDistributor" class="table table-bordered table-striped datatable">
                    <thead>
                    <tr>
                        <th>Distributor Id</th>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $data)
                    <tr>
                        <th>{{format_id($data->id,"D")}}</th>
                        <td><a href="{{route('distributor.show',$data->id)}}">{{$data->company_name}}</a></td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->phone_no}}</td>
                        <td><a href="{{route('distributor.show',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="View" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                            @if(Auth::user()->level_id != 6)
                            <a href="{{route('distributor.edit',$data->id)}}" data-toggle="tooltip" data-placement="bottom" title="Edit" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
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
    $('#exampleDistributor').DataTable( {
        language: {
             zeroRecords: "No records found",
        },
        'scrollX': true,
        'order': [[0,'desc']],
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