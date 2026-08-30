@extends('layouts.main')
@section('title', 'Profit Per Day')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Profit</li>
    <li class="breadcrumb-item active">Per Day</li>
@stop
@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Per Day Profit List
            </div>

            <div class="card-body">
                <table class="table table-responsive-sm table-bordered table-striped table-sm">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Profit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($final_transaction as $transaction)
                        <tr>
                            <td>{{standard_date($transaction['date'])}}</td>
                            <td>{{ number_format($transaction['profit'],2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <ul class="pagination">
                </ul>
            </div>

        </div>
    </div>


@endsection


