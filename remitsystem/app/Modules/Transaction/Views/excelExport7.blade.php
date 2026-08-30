<style>
    .alnright{
        text-align: right;
        margin-right: 1em;
    }
</style>
<table>
    <tr>
        <th>SN</th>
        <th>Rec_FName</th>
        <th>Rec_LName</th>
        <th>Rec_Address</th>
        <th>Rec_City</th>
        <th>Rec_state</th>
        <th>Rec_Postalcode</th>
        <th>Rec_Mobile</th>
        <th>Rec_Country</th>
        <th>Rec_BankName</th>
        <th>Rec_AccNo</th>
        <th>Rec_BankBranch</th>
        <th>NPR_Amount</th>
        <th>Rec_BankCode</th>
        <th>Note</th>
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
            <td>{{ getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td></td>
            <td>{{$beneficiary->street}}</td>
            <td>{{$beneficiary->suburb}}</td>
            <td>{{$beneficiary->state}}</td>
            <td>{{$beneficiary->postcode}}</td>
            <td>{{$beneficiary->number}}</td>
            <td>{{$beneficiary->country}}</td>
            <td>{{$beneficiary->bank_name}}</td>
            <td>{{$beneficiary->account_no}}</td>
            <td>{{$beneficiary->bsb}}</td>
            <td>{{$data['payment_amount']}}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="11"></td>
        <td>Total</td>
        <td>{{ $total }}</td>
        <td colspan="2"><td>
    </tr>
</table>
