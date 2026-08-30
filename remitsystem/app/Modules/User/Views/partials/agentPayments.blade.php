<table id="agentPayments" class="table table-bordered table-striped datatable">
    <thead>
    <tr>
        <th width="40%">Customer Name</th>
        <th width="10%">Today Commission</th>
        <th width="10%">Total Commission</th>
        <th width="10%">Outstanding Commission</th>
        <th width="10%">Action</th>
    </tr>
    </thead>
    <tbody>
@if(isset($agents))
    @foreach($agents as $agent)
         @if(getAgentCommission($agent->id)==0 )
            @else
     <tr>
            <td><a href="{{route('agent.show',$agent->id)}}">{{getAgentName($agent->id)}}</a></td>
            <td>{{getAgentCommissionToday($agent->id)}}</td>
           <td>{{getAgentCommission($agent->id)}}</td>
            <td>{{number_format(( getAgentCommission($agent->id) - getAgentPayment($agent->id)),2)}}</td>
            <td>
                <a href="{{route('agent.show',$agent->id)}}"
                   data-toggle="tooltip" data-placement="bottom" title="View"
                   class="btn btn-sm btn-success"><i
                            class="fa fa-eye"></i></a>
                <a href="{{route('agent.payment.create.individual',$agent->id)}}"
                   data-toggle="tooltip" data-placement="bottom" title="Add"
                   class="btn btn-sm btn-success"><i
                            class="fa fa-plus"></i></a>
            </td>
        </tr>
    @endif
        @endforeach
    @endif
    </tbody>

</table>
