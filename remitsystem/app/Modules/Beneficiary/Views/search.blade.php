@extends('layouts.main')
@section('title', ' Advanced Search')
@section('breadcrumb')
    @parent

    <li class="breadcrumb-item">Beneficiaries</li>
    <li class="breadcrumb-item active">Advanced Search</li>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
                <form action="{{route('beneficiary.search.result')}}" method="post" id="search-form">
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
                                <label for="sender_status">Beneficiary First Name</label>
                                <input type="text" class="form-control pull-right" name="beneficiary_first_name" id="beneficiary_first_name"
                                       value="@if(isset($search_attributes['beneficiary_first_name'])){{$search_attributes['beneficiary_first_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Beneficiary Last Name</label>
                                <input type="text" class="form-control pull-right" name="beneficiary_last_name" id="beneficiary_last_name"
                                       value="@if(isset($search_attributes['beneficiary_last_name'])){{$search_attributes['beneficiary_last_name']}} @endif"
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
                                <label for="sender_status">Sender First Name</label>
                                <input type="text" class="form-control pull-right" name="sender_first_name" id="sender_first_name"
                                       value="@if(isset($search_attributes['sender_first_name'])){{$search_attributes['sender_first_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="sender_status">Sender Last Name</label>
                                <input type="text" class="form-control pull-right" name="sender_last_name" id="sender_last_name"
                                       value="@if(isset($search_attributes['sender_last_name'])){{$search_attributes['sender_last_name']}} @endif"
                                       autocomplete="off">

                            </div>

                        </div>

                      <div class="col-md-4 col-xs-12">
                              <div class="form-group">
                                  <label for="">Added By</label>

                                  <select name="added_by" class="form-control  js-states select2-added-by">
                                      @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)<option value="0">All</option>
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

                    </div>
                </form>

            </div>
            <div class="card-body">
                <h3>Filtered Applications</h3>
                <hr>

                @if(isset($beneficiaries_data))

                    <table id="beneficiaries" class="table table-bordered table-striped">
                        <thead>
                        <tr>

                            <th>Beneficiary Id</th>
                            <th>Date Joined</th>
                            <th>Beneficiary Name</th>
                            <th>Phone</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($beneficiaries_data as $key=>$beneficiary)
                            <tr>

                                <td>{{ format_id($beneficiary->beneficiary_id, 'B') }}</td>
                                <td>{{ standard_date($beneficiary->dateAdded) }}</td>
                                <td>
                                    <a href="{{route('beneficiary.show', [$beneficiary->beneficiary_id])}}">{{$beneficiary->full_name}}</a>
                                </td>
                                <td>{{$beneficiary->number}}</td>
                                <td>{{get_user_name($beneficiary->added_by)}}</td>
                                <td><a href="{{route('beneficiary.show', [$beneficiary->beneficiary_id])}}"
                                       data-toggle="tooltip" title="View"
                                       class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                    @if(Auth::user()->level_id ==1 || Auth::user()->level_id == 2) <a
                                            href="{{route('beneficiary.edit',$beneficiary->beneficiary_id)}}"
                                            data-toggle="tooltip" title="Edit" class="btn btn-sm btn-primary"><i
                                                class="fa fa-edit"></i></a>
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
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>
@endsection
@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#beneficiaries').DataTable({
                'scrollX': true,
                order: [[0, 'desc']],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    'copy', 'csv', 'excel', 'print'
                ],
                "pageLength": 50,
                autoWidth: false
            });
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
      /*  $('.select2-multiple-beneficiary').select2({
            minimumInputLength:3,
            ajax: {
                url: '/transactions/beneficiaries/getBeneficiariesDropDownDataForSearch',
                dataType: 'json',
                delay: 1000,
                processResults: function(result){
                    return {
                        results:result
                    }
                }
            }
        });
        $('.select2-multiple-sender').select2({
            minimumInputLength:3,
            ajax: {
                url: '/transactions/senders/getSendersDropDownDataForSearch',
                dataType: 'json',
                delay: 1000,
                processResults: function(result){
                    return {
                        results:result
                    }
                }
            }
        });*/
    </script>
@stop






