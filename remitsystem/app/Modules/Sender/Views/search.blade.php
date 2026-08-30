@extends('layouts.main')
@section('title', ' Advanced Search')
@section('breadcrumb')
    @parent

    <li class="breadcrumb-item">Senders</li>
    <li class="breadcrumb-item active">Advanced Search</li>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
                <form action="{{route('sender.search.result')}}" method="post" id="search-form">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Filtered Options <input type="submit" class="btn btn-primary pull-right" value="Search">
                            </h3>

                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Sender First Name</label>
                                <input type="text" class="form-control pull-right" name="first_name" id="first_name"
                                       value="@if(isset($search_attributes['first_name'])){{$search_attributes['first_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Sender Last Name</label>
                                <input type="text" class="form-control pull-right" name="last_name" id="last_name"
                                       value="@if(isset($search_attributes['last_name'])){{$search_attributes['last_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Phone</label>
                                <input type="text" class="form-control pull-right" name="phone_number" id="phone_number"
                                       value="@if(isset($search_attributes['phone_number'])){{$search_attributes['phone_number']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Added By</label>

                                <select name="added_by" class="form-control  js-states select2-added-by">
                                    @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                        <option value="0">All</option>
                                    @endif
                                    @foreach($added_by as $user)
                                        <option value="{{$user['id']}}"
                                        <?php  if (isset($search_attributes['added_by'])):
                                            echo ($search_attributes['added_by'] == $user['id']) ? 'selected' : '';

                                        endif;
                                            ?>>
                                            {{$user['first_name']}} {{$user['last_name']}}
                                        </option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <fieldset class="form-group">
                                <label>Date Joined:</label>
                                <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                    <input type="text" class="form-control pull-right" name="date_joined" id="date-joined"
                                           value="@if(isset($search_attributes['date_joined'])){{$search_attributes['date_joined']}} @endif"
                                           autocomplete="off" placeholder="Select Date Range">
                                </div>
                            </fieldset>
                     </div>


                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Status</label>
                                <select name="sender_status[]" aria-hidden="true"
                                        class="form-control select2-multiple-transaction-status select2-multiple" multiple="">
                                    @foreach($status as $key=>$status)
                                        <option value="{{$status->id}}" <?php  if (isset($search_attributes['sender_status'])):
                                            for ($i = 0; $i <= (count($search_attributes['sender_status']) - 1); $i++):
                                                echo ($search_attributes['sender_status'][$i] == $status->id) ? 'selected' : '';
                                            endfor;
                                        endif;
                                            ?>>{{$status->name}}</option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                    </div>
                </form>

            </div>
        <div class="card-body">
                <h3>Filtered Applications</h3>
                <hr>

                @if(isset($senders_data))

                    <table id="senderSearch" class="table table-bordered table-striped">
                        <thead>
                        <tr>

                            <th>Sender Id</th>
                            <th>Date Joined</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                <th>Total Transaction</th>
                                <th>Sent Amount (AUD)</th>
                            @endif
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($senders_data as $key=>$sender)
                            <tr>

                                <td>{{ format_id($sender->sender_id, 'S') }}</td>
                                <td>{{standard_date($sender->dateAdded)}}</td>
                                <td><a href="{{route('sender.show',[$sender->sender_id])}}">{{$sender->full_name}}</a>
                                </td>
                                <td>{{$sender->email}}</td>
                                <td>{{$sender->number}}</td>
                                @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
                                    <td>{{SenderTotalSendingAmount($sender->sender_id)['transaction_count']}}</td>
                                    <td>{{SenderTotalSendingAmount($sender->sender_id)['sendingAmount']}}</td>
                                @endif
                                <td>{{get_user_name($sender->added_by)}}</td>
                                <td>{{$sender->status}}</td>
                                <td><a href="{{route('sender.show', [$sender->sender_id])}}" data-toggle="tooltip"
                                       data-placement="bottom" title="View"
                                       class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                    @if(in_array(Auth::user()->level_id, [1,2,3]))

                                        <a href="{{route('sender.edit', [$sender->sender_id])}}" data-toggle="tooltip"
                                           data-placement="bottom" title="Edit" class="btn btn-sm btn-primary"><i
                                                    class="fa fa-edit"></i></a>
                                        <form method="post"
                                              action="{{route('sender.changeStatus', [$sender->sender_id])}}">
                                            @csrf
                                            <select class="form-control" name="status_id">
                                                <option value="2">Change Status</option>
                                                @foreach($value as $key => $status)
                                                    <option value="{{$status->id}}">{{$status->name}}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                @else
                <div class="alert alert-warning" role="alert">
                        <h4>No Filtered Records!</h4>

                        <p>You can search for the applications by providing the details in the form.</p>
                    </div>

                @endif


            </div>
    </div>

@endsection
@section('page-script')
    <script type="text/javascript">
        $('#senderSearch').dataTable({
            stateSave: true,
            'scrollX': true,
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "pageLength": 50,
            order: [[0, 'desc']],

        });
        $('.select2-multiple-transaction-status').select2({
            // placeholder:'Choose Status'
            theme: "bootstrap"
        });
        var date = new Date();

        $('input[name="date_joined"]').daterangepicker({
            showDropdowns: true,
            minYear: 1901,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            maxDate: date
        });
        $('input[name="date_joined"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

        });

        $('input[name="date_joined"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    </script>
@endsection







