<table>
    <tr>
        <th>ID</th>
        <th>SENDER NAME</th>
        <th>RECEIVER NAME</th>
        <th>AMOUNT</th>
        <th>RECEIVER PHONE NO</th>
        <th>DISTRICT</th>
        <th>RECEIVE VIA</th>
          </tr>
    @foreach($data as $data)
        <tr>
            <td>{{$data['id']}}</td>
            <td>{{getSenderName($data['sender_id'])}}</td>
            <td>{{getBeneficiaryName($data['beneficiary_id'])}}</td>
            <td>{{$data['payment_amount']}}</td>
            <td>{{getBeneficiaryDetails($data['beneficiary_id'])->number}}</td>
            <td>{{$data['pickup_district']}}</td>
            <td>{{$data['payment_type']}}</td>
        </tr>
    @endforeach
</table>