@extends('layouts.main')
@section('title', 'Coupon')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Coupons</li>
    <li class="breadcrumb-item active">{{ $data->name }}</li>
@stop
@section('content')
    <div class="modal fade sendingmodal" id="sendmoney-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Coupon Details</strong>
                            <div class="pull-right">
                                @if(Auth::user()->level_id != 6)
                                <a href="{{route('coupon.create')}}"
                                   class="btn btn-success  btn-sm pull-right"><i class="fa fa-plus">
                                        Add Coupon</i></a>
                                    @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Name</dt>
                                        <dd>{{$data->name}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Code</dt>
                                        <dd>{{$data->code}}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Discount Value</dt>
                                        <dd>{{$data->discount_value}}</dd>


                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Discount Unit</dt>
                                        <dd>{{ ($data->discount_unit == 'f') ? 'Fixed' : 'Percentage'}}</dd>

                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Published</dt>
                                        <dd>{{ ($data->published == 1) ? 'Yes' : 'No' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Total Coupon</dt>
                                        <dd>{{($data->uses_total == 0)?'unlimited':$data->uses_total }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Total Unused Coupon</dt>
                                        <dd>{{ ($data->uses_total == 0)?'unlimited':$data->uses_total-$data->couponUsage->count() }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Start Date</dt>
                                        <dd>{{ $data->start_date }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>End Date</dt>
                                        <dd>{{ $data->end_date }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2">
                                    <dl>
                                        <dt>Coupon Expired</dt>
                                        <dd>{{ ($data->end_date!=null && $data->end_date < date('Y-m-d H:i:s'))? 'Yes' : 'No' }}</dd>
                                    </dl>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Coupon Details</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card" style="overflow-x: scroll;">
                                        <table id="exampleTransaction" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>

                                                <th>S.N.</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Date</th>
                                                <th>Transaction Id</th>
                                            </tr>

                                            </thead>
                                            <tbody>
                                                @php($i = 1)
                                                @foreach($data->couponUsage as $coupon)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ get_user_name($coupon->user_id) }}</td>
                                                        <td>{{ get_user_email($coupon->user_id) }}</td>
                                                        <td>{{ $coupon->created_at }}</td>
                                                        <td>{{ $coupon->transaction_id }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .due-amount .card-body {
            padding: 10px;
        }
    </style>
    <style>
        .list-group-item {
            cursor: pointer;
        }

        .tt-menu {
            width: 100% !important;
        }

        .typeahead {
            positin: relative;
        }

        .Typeahead-spinner {
            position: absolute;
            right: 93px;
            top: 23px;
            display: none;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

@endsection


