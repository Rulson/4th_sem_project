<style>
    .alnright{
        text-align: right;
        margin-right: 1em;
    }
</style>
<table>
    <tr>
        <th>SN</th>
        <th>Bank AC Name</th>
        <th>Bank AC City</th>
        <th>District</th>
        <th>State</th>
        <th>Receiver Ph</th>
        <th>Bank Name</th>
        <th>Bank AC City/District</th>
        <th>Bank AC No</th>
        <th>Receiver Get</th>
          </tr>
    @php $total = 0; @endphp
    @foreach($data as $i => $data)
        @php
            $total += $data['payment_amount'];
            $sender = getSenderDetails($data['sender_id']);
            $beneficiary = getBeneficiaryDetails($data['beneficiary_id']);
        @endphp
        <tr>
            <td>{{$i+1}}</td>
            <td>{{$beneficiary->account_name}}</td>
            <td>{{$beneficiary->street}}</td>
            <td>{{$beneficiary->suburb}}</td>
            <td>{{$beneficiary->state}}</td>
            <td>{{$beneficiary->number}}</td>
            <td>{{$beneficiary->bank_name}}</td>
            <td>{{$beneficiary->bsb}}</td>
            <td>A/C - {{$beneficiary->account_no}}</td>
            <td>{{$data['payment_amount']}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="8"></td>
        <td>Total</td>
        <td>{{ $total }}</td>
    </tr>
</table>
