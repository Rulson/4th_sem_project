<table>
    <tr>
        <th>Id</th>
        <th>Order Date</th>
        <th>Receiver</th>
        <th>Sender Pay</th>
        <th>Receiver Get</th>
        <th>Customer Rate</th>
        <th>Agent Rate</th>
    </tr>
    @foreach($data as $data)
        <tr>
            <td>{{$data['id']}}</td>
            <td>{{format_date_order_page(standard_date($data['transaction_date']))}}</td>
            <td>{{getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td>{{$data['total_to_pay']}}</td>
            <td>{{$data['payment_amount']}}</td>
            <td>{{$data['exchange_rate']}}</td>
            @php
                $agent_transaction = \App\Modules\Agent\Models\AgentTransaction::where('transactions_id',$data['id'])->first();
               if($agent_transaction){
                $agent_rate=$agent_transaction->exchange_rate;
                }else{
                   $agent_rate='';
                }
            @endphp
            <td>{{$agent_rate}}</td>
        </tr>
    @endforeach
</table>