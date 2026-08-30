<table id="distributorBalance" class="table table-bordered table-striped datatable">
    <thead>
    <tr>
        <th  width="30%">Distributor</th>
        <th  width="20%">Remaining Balance</th>
        <th  width="10%"> Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($distributor_offices))
@foreach($distributor_offices as $distributor)
   <tr>
        <td><a href="{{route('distributor.show',$distributor->companies_id)}}">{{getDistributorOfficeName($distributor->companies_id)}}</a></td>
   <td>{{number_format((getDistributorPayment($distributor->companies_id) - getPaidForTransaction($distributor->companies_id)),2)}}</td>
        <td>
            <a href="{{route('distributor.show',$distributor->companies_id)}}"
               data-toggle="tooltip" data-placement="bottom" title="View"
               class="btn btn-sm btn-success"><i
                        class="fa fa-eye"></i></a>
            <a href="{{route('distributor.payment.create.individual',$distributor->companies_id)}}"
               data-toggle="tooltip" data-placement="bottom" title="Add"
               class="btn btn-sm btn-success"><i
                        class="fa fa-plus"></i></a>
        </td>

    </tr>
    @endforeach
        @endif
    </tbody>

</table>
