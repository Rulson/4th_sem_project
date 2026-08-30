<table>
    <tr>
        <th>Id</th>
        <th>Receiver Name</th>
        <th>Receiver Ph</th>
        <th>Bank Name</th>
        <th>Bank AC Name</th>
        <th>Bank AC No</th>
        <th>Bank AC City/ District</th>
{{--        <th>Pickup District</th>--}}
{{--        <th>NRS-</th>--}}
        <th>Receiver Get</th>
{{--        <th>Receive Via</th>--}}
    </tr>
    @foreach($data as $data)
        <tr>
            <td>{{format_id($data['id'], "TN")}}</td>
            <td>{{getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td>{{getBeneficiaryDetails($data['beneficiary_id'])->number}}</td>
            <td>@if($data['payment_type'] =='Bank Transfer') {{getBeneficiaryDetails($data['beneficiary_id'])->bank_name}} @else -- @endif</td>
            <td>@if($data['payment_type'] =='Bank Transfer') {{getBeneficiaryDetails($data['beneficiary_id'])->account_name}} @else -- @endif</td>
            <td>@if($data['payment_type'] =='Bank Transfer') A/C - {{getBeneficiaryDetails($data['beneficiary_id'])->account_no}}@else -- @endif</td>
            <td>@if($data['payment_type'] =='Bank Transfer'){{getBeneficiaryDetails($data['beneficiary_id'])->bsb}}@else -- @endif</td>
{{--            <td>@if($data['payment_type'] =='Bank Transfer') -- @else {{$data['pickup_district']}} @endif</td>--}}
{{--            <td>NRS-</td>--}}
            <td>{{$data['payment_amount']}}</td>
{{--            <td>{{$data['payment_type']}}</td>--}}
        </tr>
    @endforeach
</table>
