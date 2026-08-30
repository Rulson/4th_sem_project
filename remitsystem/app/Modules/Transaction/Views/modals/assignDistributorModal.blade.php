<div class="modal fade assignDis" id="assignDistributor" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Assign Distributor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('transactions.assign.distributors')}}"  class="form-horizontal" id="assignDistributorsForm">
                {{csrf_field()}}
                <div class="modal-body">
                    {{ Form::hidden('transactionId', $transaction->id) }}
                    {{ Form::hidden('total_amount', $transactionDetail->payment_amount) }}
                        @php
                        if($assigned.length) {
                            foreach($assigned->slice(0,1) as $distributor) { @endphp
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
                                {{ Form::text('amount[]', $distributor->amount, ['class' => 'form-control', 'placeholder' => 'Enter Amount','id'=>'form-amount','readonly']) }}
                                </div>
                        @php
                            }
                        } else { @endphp
                            <div id="InputsWrapper">
                            @php  $distributor_list = distributorList();
                            $distributor_list[0]='Select Distributor';
                            ksort($distributor_list); @endphp
                            $assigned[0]->distributor_office_id
                            <div class="form-group" >
                                {{ Form::label('distributor_id', 'Select Distributor', ['class' => 'control-label']) }}
                                {{ Form::select('distributor_id[]', $distributor_list, null, ['class' => 'form-control','id'=>'distributor_id']) }}
                            </div>
                            <div class="form-group" >
                                {{ Form::label('amount', 'Assign Amount', ['class' => 'control-label']) }}
                                {{ Form::text('amount[]', null, ['class' => 'form-control ', 'placeholder' => 'Enter Amount','id'=>'form-amount','readonly']) }}
                                </div>
                            </div>
                        @php } @endphp

                        <div id="AddMoreFileId">
                            <a href="#" id="AddMoreFileBox" class="btn btn-info btn-sm">+</a><br><br>
                        </div>
                        <div id="lineBreak"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit_distributor_assign" class="btn btn-primary btn-submit" >Save</button>
                </div>
                          </form>
        </div>
    </div>
</div>
<script>
    var distributors =<?php echo distributorListObject() ?>;

    var options ='';
    $(document).ready(function() {
        $.each(distributors, function (key, val) {
            // alert(key + val);
            options += '<option value="'+val.id+'"> '+val.company_name +'</option> <br>';
        });

        var MaxInputs       = 7; //maximum extra input boxes allowed
        var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
        var AddButton       = $("#AddMoreFileBox"); //Add button ID

        var x = InputsWrapper.length; //initlal text box count
        var FieldCount=1; //to keep track of text box added

//on add input button click

        $(AddButton).click(function (e) {
            //max input box allowed
            if(x <= MaxInputs) {
                FieldCount++; //text box added ncrement
                //add input box
                $(InputsWrapper).after('<div id="removeWrapper"><div class="form-group"><label>Select Distributor</label><select class="form-control" name="distributor_id[]" id="distributor_id_'+ FieldCount +'">'+options+'</select></div>' +
                    '<div class="form-group"><label>Assign Amount</label><input type="text" class="form-control amount" name="amount[]" value="0" id="amount_'+ FieldCount +'" placeholder="Enter Amount" /></div><a href="#" class="btn btn-danger btn-sm removeclass">-</a></div>');
               // $(InputsWrapper).append('<div><input type="text" name="mytext[]" id="field_'+ FieldCount +'"/> <a href="#" class="removeclass">-</a></div>');
                x++; //text box increment
                var total_amount = parseFloat($('input[name="total_amount"]').attr('value'));

                $("#AddMoreFileId").show();

                $('AddMoreFileBox').html("Add field");

                // Delete the "add"-link if there is 3 fields.
                if(x == 5) {
                    $("#AddMoreFileId").hide();
                    $("#lineBreak").html("<br>");
                }

                $(".amount").on("keyup", function() {

                    if($(this).val() == ''){


                        $('#form-amount').attr('value', total_amount );

                    }
                    else if($(this).val() > total_amount)
                    {

                        toastr.error('Amount cannot be greater than '+total_amount, {
                            closeButton: true,
                            progressBar: true,
                        });
                        $('#form-amount').attr('value', total_amount );
                        $(this).val(0);

                    }
                    else{
                        var sum = 0;
                        $('.amount').each(function(){
                            sum += parseFloat(this.value);
                        });

                        $('#form-amount').attr('value', total_amount - sum);
                    }





                });
               /* $('.amount').keyup(function () {

                    if(parseInt($(this).val()) < total_amount) {
                        $('#form-amount').attr('value', total_amount - parseInt($(this).val()));
                    }

                });*/
               /* $('.amount').keydown(function () {
                    alert($(this).val().length);
                        if(parseInt($(this).val()) < total_amount) {
                            $('#form-amount').attr('value', total_amount - parseInt($(this).val()));
                        }


                });
           */
                if($('.amount').val() == ''){

                    $('#form-amount').attr('value', total_amount );

                }

            }
            return false;

        });

        $("body").on("click",".removeclass", function(e){ //user click on remove text
            if( x > 1 ) {
                $(this).parent('div').remove();
                var total_amount = parseFloat($('input[name="total_amount"]').attr('value'));
                var sum = 0;
                $('.amount').each(function(){
                    sum += parseFloat(this.value);
                });
                 //remove text box
                x--; //decrement textbox

                $("#AddMoreFileId").show();

                $("#lineBreak").html("");

                // Adds the "add" link again when a field is removed.
                $('AddMoreFileBox').html("Add field");
                $('#form-amount').attr('value', total_amount - sum );
            }
            return false;
        })

    });
</script>