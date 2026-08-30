@extends('layouts.main')
@section('title', 'Transaction')

@section('breadcrumb')
    @parent
    <li>Advanced Search</li>

@stop
@section('content')
    <section class="content">
               <div class="box box-primary">

            <div class="box-body">

            </div>
            <div class="box-body box-profile">
                <h3>Filtered Applications</h3>
                <hr>

                                   <br>
                    <table id="exampleTransactionSearch" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Company name</th>
                            <th>Requirement Id</th>
                            <th>Turn Around Time</th>
                            <th>Documents</th>
                            <th>Special Note</th>
                            <th>Lodgement Procedure</th>
                            <th>Commission Processing Timeframe</th>
                            <th>Institution id</th>

                        </tr>
                        </thead>
                        <tbody>


                            <tr>

                                                          </tr>

                        </tbody>

                    </table>


            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
    </section>
@endsection
