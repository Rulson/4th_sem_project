<table class="table table-responsive-sm table-bordered table-striped table-sm orders">
    <thead>
    <tr>
        @if(!in_array(Auth::user()->level_id, [6]))<th><input type="checkbox" id="check-all"/></th>@endif
        @if(Auth::user()->level_id != 6)<th>Action</th>@endif
        <th>Trans Id</th>
        <th>Date</th>
        <th>Status</th>
        @if(Auth::user()->level_id == 7)
        <th>Verified</th>
        @endif
        @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2 || Auth::user()->level_id == 8)
            <th>Quick Note</th>
        @endif
        <th>Sender</th>
            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 8)
        <th>Sender Phone</th>
            @endif
        <th>Sender Pay</th>
        <th>Receiver</th>
        <th>Receiver Get</th>
        @if(in_array(Auth::user()->level_id, [1,2,6,7,8]))
            <th>Distributor</th>
            <th>Agent</th>
        @endif
{{--        <th>Receive Via</th>--}}
{{--        <th>Pickup Place</th>--}}
        <th>Receiver Phone</th>
        <th>Bank Name</th>
        <th>Bank AC Name</th>
        <th>Bank AC No</th>
        <th>Bank AC City</th>
        <th>Cust Rate</th>
        @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 8)
            <th>Agent Rate</th>
            <th>Agent Commission</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $data)
        <tr>
            @if(!in_array(Auth::user()->level_id, [6]))
                <td><input type="checkbox" value="{{$data->transaction_id}}" class="checks"/></td>
            @endif
            @if(Auth::user()->level_id != 6)<td>
                @if (in_array(Auth::user()->level_id , [1, 2, 3, 5, 7,8]))
                    @php
                        $agent = App\Modules\Agent\Models\Agent::where('user_id', $data->added_by)->first();
                        $user = App\Modules\User\Models\User::withTrashed()->leftJoin('person', 'person.id', '=', 'users.person_id')->where('users.id', '=', $data->added_by)->select('person.first_name', 'person.last_name', 'person.email')->first();
                        $client = App\Modules\User\Models\User::where('level_id', 5)->where('id', $data->added_by)->first();
                        $transactionReceiptDocument = App\Modules\Transaction\Models\TransactionDocument::where('transactions_id', $data->transaction_id)->orderBy('id', 'desc')->first();
                        $image = (isset($transactionReceiptDocument) && $transactionReceiptDocument->file_name != '') ?  $transactionReceiptDocument['file_name'] : '';
                    @endphp
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" id="dropdownMenuButton" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                class="fa fa-tasks"></i></button>

                            @if(isset($agent))
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if (Auth::user()->level_id != 7)
                                        <a class="dropdown-item" href="#"
                                        onclick="actionbox(1,'{{$data->transaction_id}}','{{$agent['user_id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                        <a class="dropdown-item" href="#"
                                        onclick="actionbox(2,'{{$data->transaction_id}}','{{$agent['user_id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                    @endif
                                    @if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <a class="dropdown-item" tabindex="-1"
                                           data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                                           data-keyboard="false"
                                           data-url="{{route('transaction.transaction.edit',$data->transaction_id)}}">Edit</a>
                                        <a
                                            class="dropdown-item" href="#"
                                            onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Invoice</a>

                                    @endif
                                    <a href="#" class="dropdown-item" tabindex="-1" data-toggle="modal"
                                       data-target="#TransactionReceiptImage"
                                       data-img="{{asset('TransactionIdentification/'.$image)}}"
                                       >View Receipt</a>
                                </div>
                            @elseif (isset($client))
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if (Auth::user()->level_id != 7)
                                    <a class="dropdown-item" href="#"
                                        onclick="actionbox(1,'{{$data->transaction_id}}','{{$client['id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                    @endif
                                    <a class="dropdown-item" href="#"
                                       onclick="actionbox(2,'{{$data->transaction_id}}','{{$client['id']}}','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                    @if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <a class="dropdown-item" tabindex="-1"
                                           data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                                           data-keyboard="false"
                                           data-url="{{route('transaction.transaction.edit',$data->transaction_id)}}">Edit</a><a
                                            class="dropdown-item" href="#"
                                            onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Invoice</a>

                                    @endif
                                    <a href="#" class="dropdown-item" tabindex="-1" data-toggle="modal"
                                       data-target="#TransactionReceiptImage"
                                       data-img="{{asset('TransactionIdentification/'.$image)}}"
                                    >View Receipt</a>

                                </div>
                            @else
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if (Auth::user()->level_id != 7)
                                    <a class="dropdown-item" href="#"
                                       onclick="actionbox(1,'{{$data->transaction_id}}','','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">Comment</a>
                                    @endif
                                    <a class="dropdown-item" href="#"
                                       onclick="actionbox(2,'{{$data->transaction_id}}','','','{{$user->first_name}} {{ $user->last_name}}  ({{$user->email}})')">View</a>
                                    @if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <a class="dropdown-item" tabindex="-1"
                                           data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                                           data-keyboard="false"
                                           data-url="{{route('transaction.transaction.edit',$data->transaction_id)}}">Edit</a>
                                        <a
                                            class="dropdown-item" href="#"
                                            onclick="actionbox(3,'{{$data->transaction_id}}','','','')">Invoice</a>
                                    @endif
                                    <a href="#" class="dropdown-item" tabindex="-1" data-toggle="modal"
                                       data-target="#TransactionReceiptImage"
                                       data-img="{{asset('TransactionIdentification/'.$image)}}"
                                    >View Receipt</a>
                                </div>
                            @endif
                        @endif
                        </div>
                    </div>

            </td>@endif
            <td>{{$data->transaction_id}}</td>
            <td>{{format_date_order_page($data->transactionDate)}}</td>
            <td>
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8)
                    <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                       data-url="{{route('changeStatus.modal',[$data->transaction_id])}}"
                       class="cursor-pointer badge {{str_replace(' ', '_', strtolower(getStatusName($data->status_id)))}}"
                       title="Status">{{getStatusName($data->status_id)}}</a>
                @else
                    <span class="badge {{str_replace(' ', '_', strtolower(getStatusName($data->status_id)))}}">{{getStatusName($data->status_id)}}</span>
                @endif
            </td>
             @if(Auth::user()->level_id == 7)
            <td>
                <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                data-url="{{route('changeIsVerified.modal',[$data->transaction_id])}}"
                class="cursor-pointer badge {{$data->is_verified == 1 ? 'paid': 'pending'}}"
                title="Status">{{$data->is_verified == 1 ? 'Verified':'Unverified'}}</a>
            </td>
            @endif

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2  || Auth::user()->level_id == 8)
                <td>
                    <form id="add-staffnote-form-{{$data->transaction_id}}"
                          action="{{route('transaction.adminStaffNotes.store', $data->transaction_id)}}">
                        {{csrf_field()}}
                        <span style="min-width:100px;max-width:120px;">

                            <textarea
                                    style="padding: 0px 3px; color:black;font-weight:bold;height: 28px; width:200px!important; font-size: 13px;"
                                    name="admin_staff_notes"
                                    class="form-control" id="staff_note_textarea" data-txn="{{$data->transaction_id}}"
                                    placeholder="Quick note">{{getAdminStaffNote($data->transaction_id)}}</textarea>
</span>
                        <span>
</span>
                    </form>
                </td>
            @endif
            <td>{{$data->sender_name}}</td>
                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 8)
                    <td>{{$data->sender_phone}}</td>
                @endif

            <td style="font-weight:bolder;">{{$data->totalAmount}}</td>
            <td>{{$data->beneficiary_name}}</td>
            <td style="font-weight:bold;">{{$data->paymentAmount}}</td>
            @if(in_array(Auth::user()->level_id, [1,2,6,7,8]))
                <td>
                    @php
                    $distributor_transaction = \App\Modules\Distributor\Models\DistributorTransaction::where('transaction_id',$data->transaction_id)->first();
                    @endphp
                    @if($distributor_transaction)
                        @php
                            $assign_distributor = App\Modules\Distributor\Models\DistributorsAssign::where('transactions_id', $data->transaction_id);
                        @endphp

                        @if ($assign_distributor->count() == 1)
                            @php
                                $assigned_name = $assign_distributor->first();
                                $company =  App\Modules\Distributor\Models\DistributorOffice::where('id',$assigned_name->distributor_office_id)->first();
                                $company_name= getDistributorOfficeName($company->companies_id);
                                $distributor_class = 'distributor_'.($company->companies_id > 15 ? 0 : $company->companies_id);
                            @endphp
                            @if(!in_array(Auth::user()->level_id, [6,7]))
                                <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                                data-keyboard="false"
                                data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                class="badge badge-color-code {{$distributor_class}}"
                                title="Distributor">{{$company_name}}</a>
                                @else
                                <span class="badge badge-color-code {{$distributor_class}}">{{$company_name}}</span>
                            @endif
                        @elseif ($assign_distributor->count() > 1)
                            @if(!in_array(Auth::user()->level_id, [6,7]))
                                <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static"
                                data-keyboard="false"
                                data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                class="badge badge-warning" title="Distributor">Multiple</a>
                            @else
                                <span class="badge badge-warning">Multiple</span>
                            @endif
                        @else
                            @if(!in_array(Auth::user()->level_id, [6,7]))
                                <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                                    data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                    class="badge badge-warning assign-distributor" title="Assign Distributor">Distributor</a>
                            @else
                                <span class="badge badge-warning assign-distributor">Distributor</span>
                            @endif
                        @endif
                    @else
                        @if(!in_array(Auth::user()->level_id, [6,7]))
                            <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                                data-url="{{route('transactions.assign.distributors.edit', [$data->transaction_id])}}"
                                class="badge badge-warning assign-distributor" title="Assign Distributor">Distributor</a>
                        @else
                                <span class="badge badge-warning assign-distributor">Distributor</span>
                        @endif
                    @endif
                </td>
                <td>
                    @php
                        $agent_transaction = \App\Modules\Agent\Models\AgentTransaction::where('transactions_id',$data->transaction_id)->first();
                        if($agent_transaction){
                            $val = getAgentName($agent_transaction->agents_id);
                        }else{
                            $val = "Collector";
                        }
                    @endphp
                    @if(!in_array(Auth::user()->level_id, [6,7]))
                        <a data-toggle="modal" data-target="#sendmoney-modal" data-backdrop="static" data-keyboard="false"
                        data-url="{{route('transactions.assign.agent', [$data->transaction_id])}}" href="#"
                        class="badge badge-warning badge-black" title="Distributor">{{$val}}</a>
                    @else
                        <span class="badge badge-warning badge-black">{{$val}}</span>
                    @endif
                </td>
            @endif
{{--            <td>{{$data->payment_type}}</td>--}}


{{--            <td> @if($data->payment_type =='Bank Transfer')  @else {{$data->pickup_district}} @endif</td>--}}
            <td>{{$data->beneficiary_phone}}</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->bank_name}}@else @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->account_name}}@else  @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->account_no}} @else @endif</td>
            <td>@if($data->payment_type =='Bank Transfer'){{$data->bsb}} @else  @endif</td>
            <td>{{$data->exchangeRate}}</td>
            @if(Auth::user()->level_id ==1 || Auth::user()->level_id ==2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 8)
                <td>{{$data->agentRate}}</td>
                <td>{{$data->agentCommission}}</td>
            @endif
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
