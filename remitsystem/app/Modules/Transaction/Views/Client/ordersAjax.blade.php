<table class="table table-responsive-sm table-bordered table-striped table-sm orders">
    <thead>
    <tr>

        <th>Action</th>
        <th>Trans Id</th>
        <th>Date</th>
        <th>Status</th>
        <th>Sender</th>
        <th>Sender Pay</th>
        <th>Receiver</th>
        <th>Receiver Get</th>
        <th>Receive Via</th>
        <th>Pickup Place</th>
        <th>Receiver Phone</th>
        <th>Bank Name</th>
        <th>Bank AC Name</th>
        <th>Bank AC No</th>
        <th>Bank AC City</th>
        <th>Cust Rate</th>

    </tr>
    </thead>
    <tbody>
    @foreach($orders as $data)
        <tr>

           <td>

                    @php
                        $user = App\Modules\User\Models\User::leftJoin('person', 'person.id', '=', 'users.person_id')->where('users.id', '=', $data->added_by)->select('users.id','person.first_name', 'person.last_name', 'person.email')->first();

                           $transactionReceiptDocument = App\Modules\Transaction\Models\TransactionDocument::where('transactions_id', $data->transaction_id)->orderBy('id', 'desc')->first();
                           $image = (isset($transactionReceiptDocument) && $transactionReceiptDocument->file_name != '') ?  $transactionReceiptDocument['file_name'] : '';
                    @endphp
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" id="dropdownMenuButton" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                class="fa fa-tasks"></i></button>


                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <a class="dropdown-item" href="#"
                                        onclick="actionbox(1,'{{$data->transaction_id}}','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>

                                    <a class="dropdown-item" href="#"
                                       onclick="actionbox(2,'{{$data->transaction_id}}','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>

                                    <a href="#" class="dropdown-item" tabindex="-1" data-toggle="modal"
                                       data-target="#TransactionReceiptImage"
                                       data-img="{{asset('TransactionIdentification/'.$image)}}"
                                    >View Receipt</a>

                                </div>
                        </div>
                    </div>

            </td>
            <td>{{$data->transaction_id}}</td>
            <td>{{format_date_order_page($data->transactionDate)}}</td>
            <td>

                    <span class="badge {{str_replace(' ', '_', strtolower(getStatusName($data->status_id)))}}">{{getStatusName($data->status_id)}}</span>

            </td>

            <td>{{$data->sender_name}}</td>

            <td style="font-weight:bolder;">{{$data->totalAmount}}</td>
            <td>{{$data->beneficiary_name}}</td>
            <td style="font-weight:bold;">{{$data->paymentAmount}}</td>
            <td>{{$data->payment_type}}</td>


            <td> @if($data->payment_type =='Bank Transfer')  @else {{$data->pickup_district}} @endif</td>
            <td>{{$data->beneficiary_phone}}</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->bank_name}}@else @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->account_name}}@else  @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->account_no}} @else @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->bsb}} @else  @endif</td>
            <td>{{$data->exchangeRate}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@if($orders->firstItem() == '')
@else
    Showing {{$orders->firstItem()}} to {{$orders->lastItem()}} of {{$orders->total()}} entries

@endif
<ul class="pagination">
    {{ $orders->appends(Request::except('page'))->links() }}
</ul>
