<style>
    .alnright{
        text-align: right;
        margin-right: 1em;
    }
</style>
<table>
    <tr>
        <th>SN</th>
        <th>Full Name</th>
        <th>Address</th>
        <th>Suburb</th>
        <th>State</th>
        <th>Postcode</th>
        <th>Phone</th>
        <th>DOB</th>
        <th>Expiry Date</th>
        <th>Issued By</th>
        <th>ID Number</th>
        <th>ID Type</th>
        <th>Receiver Name</th>
        <th>Receiver Phone</th>
        <th>Bank Name</th>
        <th>Bank AC Name</th>
        <th>Bank AC no</th>
        <th>Bank AC District/City</th>
        <th>NRS-</th>
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
            <td>{{ $sender->full_name }}</td>
            <td>{{$sender->street}}</td>
            <td>{{$sender->suburb}}</td>
            <td>{{$sender->state}}</td>
            <td>{{$sender->postcode}}</td>
            <td>{{$sender->number}}</td>
            <td>{{format_only_date($sender->dob)}}</td>
            <td>{{format_only_date($sender->expiry_date)}}</td>
            <td>{{$sender->issued_by}}</td>
            <td>{{$sender->id_number}}</td>
            <td>{{$sender->name}}</td>
            <td>{{ getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td>{{$beneficiary->number}}</td>
            <td>{{$beneficiary->bank_name}}</td>
            <td>{{$beneficiary->account_name}}</td>
            <td>{{$beneficiary->account_no}}</td>
            <td>{{$beneficiary->bsb}}</td>
            <td>NRS-</td>
            <td>{{$data['payment_amount']}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="17"></td>
        <td>Total</td>
        <td>{{ $total }}</td>
    </tr>
</table>
