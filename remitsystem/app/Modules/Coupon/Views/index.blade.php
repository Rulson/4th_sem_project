@extends('layouts.main')
@section('title', 'All Coupons')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item active">All Coupons</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Coupons
                <div class="pull-right">
                    <a href="{{ route('coupon.create') }}" class="btn btn-primary btn-sm">Add
                        New Coupon</a>
                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped table-bordered datatable table-sm" id="users">
                    <thead>
                    <tr>
                        <th> Id</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Discount Value</th>
                        <th>Discount Unit</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Published</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $coupon)

                        <tr>
                            <th>{{ $coupon->id }}</th>
                            <td>{{$coupon->name}}</td>
                            <td>{{$coupon->code}}</td>
                            <td>{{$coupon->discount_value}}</td>
                            <td>{{($coupon->discount_unit == 'f')? 'Fixed' : 'Percentage'}}</td>
                            <td>{{$coupon->start_date}}</td>
                            <td>{{$coupon->end_date}}</td>
                            <td>{{($coupon->published == 1) ? 'Yes' : 'No'}}</td>
                            <td>
                                <a href="{{route('coupon.show', [$coupon->id])}}" data-toggle="tooltip" title="Show" data-placement="bottom" class="btn btn-sm btn-success"><i
                                        class="fa fa-eye"></i></a>
                                <a href="{{route('coupon.edit', [$coupon->id])}}" data-toggle="tooltip" title="Edit" data-placement="bottom" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





