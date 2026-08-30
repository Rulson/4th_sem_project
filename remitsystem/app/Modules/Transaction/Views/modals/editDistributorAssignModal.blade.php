<div class="modal-header">
                <h4 class="modal-title">Edit Distributor Assign</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
            </div>
            <form method="post"
                  action="{{route('transactions.assign.distributors')}}"  class="form-horizontal" id="assignDistributorsForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    {{ Form::hidden('transactionId', $transaction->id) }}
                    {{ Form::hidden('total_amount', $transactionDetail->payment_amount) }}
                    @php
                    if(count($assigned)) {
                        foreach($assigned as $k => $distributor) { @endphp
                        <div class="{{ $k ? 'removeWrapper' : 'inputsWrapper' }}">
                            <div class="form-group" >
                                {{ Form::label('distributor_id', 'Select Distributor', ['class' => 'control-label']) }}
                                <select name="distributor_id[]" class="control-label select2">
                                <option value="0">Select Distributor</option>
                                @foreach(distributorList() as $key=>$value)
                                    <option value="{{$key}}" {{($key == $distributor->distributor_office_id)? 'selected': ''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" >
                                {{ Form::label('amount', 'Assign Amount', ['class' => 'control-label']) }}
                                {{ Form::text('amount[]', $distributor->amount, ['class' => 'amount form-control', 'placeholder' => 'Enter Amount',$k ? '' : 'readonly']) }}
                                
                                @if($k) <a href="#" class="btn btn-danger btn-sm removeclass" id="removeclone">-</a> @endif
                            </div>
                        </div>
                    @php
                        }
                    } else { @endphp
                        <div class="inputsWrapper">
                            @php  $distributor_list = distributorList();
                            $distributor_list[0]='Select Distributor';
                            ksort($distributor_list); @endphp
                            <div class="form-group" >
                                {{ Form::label('distributor_id', 'Select Distributor', ['class' => 'control-label']) }}
                                {{ Form::select('distributor_id[]', $distributor_list, null, ['class' => 'form-control','id'=>'distributor_id']) }}
                            </div>
                            <div class="form-group" >
                                {{ Form::label('amount', 'Assign Amount', ['class' => 'control-label']) }}
                                {{ Form::text('amount[]', $transactionDetail->payment_amount, ['class' => 'form-control amount', 'placeholder' => 'Enter Amount','readonly']) }}
                            </div>
                        </div>
                    @php } @endphp
                    <div id="AddMoreFileId">
                        <a href="#" id="AddMoreFileBox" class="btn btn-info btn-sm">+</a><br><br>
                    </div>
                    <div id="lineBreak"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>

<script>
    $('.select2').select2({
        'theme':'bootstrap',
        width:'100%'
    });
    var total_amount="{{$transactionDetail->payment_amount}}";
    var distributors =<?php echo distributorListObject() ?>;
    var options = '';
    function calSum() {
        sumAmt = 0;
        $('.amount').each(function(k, v) {
            if(k) sumAmt += $(v).val();
            console.log(sumAmt);
        });
        calRem();
    }
    function calRem() {
        remAmt = parseFloat(total_amount);
        $('.amount').each(function(k, v) {
            if(k) remAmt -= $(v).val();
        });

    }


    $(document).ready(function() {
        $.each(distributors, function (key, val) {
            options += '<option value="'+val.id+'"> '+val.company_name +'</option> <br>';
        });

        var MaxInputs       = 7; //maximum extra input boxes allowed
        var InputsWrapper   = $(".InputsWrapper"); //Input boxes wrapper ID
        var AddButton       = $("#AddMoreFileBox"); //Add button ID

        var x = InputsWrapper.length; //initlal text box count

        var FieldCount=1; //to keep track of text box added

        // function emptyDistributorAssign() {
        //     $(InputsWrapper).html('');
        // }
//on add input button click
        $('#AddMoreFileBox').on('click', function (e) {
            $('#AddMoreFileId').before('<div class="removeWrapper"><div class="form-group"><label>Select Distributor</label><select class="form-control" name="distributor_id[]" id="distributor_id_'+ FieldCount +'">'+options+'</select></div>' +
                '<div class="form-group"><label>Assign Amount</label><input type="text" class="form-control amount" name="amount[]" value="0" placeholder="Enter Amount" /></div><a href="#" class="btn btn-danger btn-sm removeclass">-</a></div>');
            $(InputsWrapper).append('<div><input type="text" name="mytext[]" id="field_'+ FieldCount +'"/> <a href="#" class="removeclass" id="removeclone">-</a></div>');
            $("#AddMoreFileId").show();
            $('AddMoreFileBox').html("Add field");
            if(x == 5) {
                $("#AddMoreFileId").hide();
                $("#lineBreak").html("<br>");
            }
        });
        $(document).on("keyup",'.amount', function() {
           calSum();
           console.log(sumAmt);
           console.log(total_amount);
            if(sumAmt > parseFloat(total_amount)) {
                $(this).val(0);
                toastr.error('Amount cannot be greater than '+ parseFloat(total_amount), {
                    closeButton: true,
                    progressBar: true,
                });
                calRem();
            }
            $(".amount:first").val(remAmt);
        });
        $(document).on("click",'.removeclass', function(e){//user click on remove text
            $(this).closest('.removeWrapper').remove(); //remove text box
            calSum();

            // $("#AddMoreFileId").html('<a href="#" id="AddMoreFileBox" class="btn btn-info btn-sm">+</a><br><br>');

            $("#lineBreak").html("");
            $("#AddMoreFileId").show();

            // Adds the "add" link again when a field is removed.
            $('AddMoreFileBox').html("Add field");
            $(".amount:first").val(remAmt);
            console.log(remAmt, sumAmt);
        });

    });
</script>