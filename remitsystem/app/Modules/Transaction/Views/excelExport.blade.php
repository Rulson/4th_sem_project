<table>
    <tr>
        <th>Id</th>
        <th>Order Date</th>
        <td>Sender Id</td>
        <th>Sender</th>
        <th>Sender Pay</th>
        <th>Receiver</th>
        <th>Receiver Get</th>
        <th>Distributor</th>
        <th>Collector</th>
        <th>Receive Via</th>
        <th>Customer Rate</th>
        <th>Cost Rate</th>
        <th>Agent Rate</th>
        <th>Service Charge</th>
    </tr>
    @foreach($data as $data)
        <tr>
            <td>{{$data['id']}}</td>
            <td>{{format_date_order_page(standard_date($data['transaction_date']))}}</td>
            <td>{{format_id($data['sender_id'], "S")}}</td>
            <td>{{getSenderName($data['sender_id'])}}</td>
            <td>{{$data['total_to_pay']}}</td>
            <td>{{getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td>{{$data['payment_amount']}}</td>
            @php $assign_distributor = App\Modules\Distributor\Models\DistributorsAssign::where('transactions_id', $data['id']); @endphp
            @if ($assign_distributor->count() == 1)
                @php
                    $assigned_name = $assign_distributor->first();
                    $company =  App\Modules\Distributor\Models\DistributorOffice::where('id',$assigned_name->distributor_office_id)->first();
                    $company_name= getDistributorOfficeName($company->companies_id);
                @endphp
            <td>{{$company_name}}</td>
            @elseif ($assign_distributor->count() > 1)
                <td>Multiple</td>
            @else
       <td></td>     @endif
            @php
                $agent_transaction = \App\Modules\Agent\Models\AgentTransaction::where('transactions_id',$data['id'])->first();
               if($agent_transaction){
                $val = getAgentName($agent_transaction->agents_id);
                $agent_rate=$agent_transaction->exchange_rate;
                }else{
                $val = "";
                $agent_rate='';
                }
            @endphp
            <td>{{$val}}</td>
            <td>{{$data['payment_type']}}</td>
            <td>{{$data['exchange_rate']}}</td>
            <td>{{$data['cost_rate']}}</td>
            <td>{{$agent_rate}}</td>
            <td>{{$data['service_charge']}}</td>
        </tr>
    @endforeach
</table>