<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Add Identifications</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
</div>

{!!Form::open(array('route' => ['sender.store.new.identification',$senderId],'method' => 'post','files'=>'true','id'=>'sender-new-identification-form'))!!}

<div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <strong>Identification Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('id_type')) {{'has-error'}} @endif"
                                     id="id_type_div">
                                    {!!Form::label('id_type', 'Id Type (*)', array('class' => 'control-label','id'=>'idtype-label')) !!}
                                    {!!Form::select('id_type', identificationTypes(),null, array('class' => 'form-control', 'id'=>'id_type','placeholder'=>'Select Id Type','required'))!!}
                                    @if($errors->has('id_type'))
                                        {!! $errors->first('id_type', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('issued_by')) {{'has-error'}} @endif"
                                     id="issued_by_div">
                                    {!!Form::label('issued_by', 'Issued By (*)', array('class' => 'control-label','id'=>'issuedby-label')) !!}
                                    <select name="issued_by" class="form-control" id="issued_by" required><option></option></select>
                                   {{-- {!!Form::text('issued_by', null, array('class' => 'form-control', 'id'=>'issued_by', 'placeholder'=>'Enter Issued By'))!!}--}}
                                    @if($errors->has('issued_by'))
                                        {!! $errors->first('issued_by', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('id_number')) {{'has-error'}} @endif"
                                     id="id_number_div">
                                    {!!Form::label('id_number', 'Id Number (*)', array('class' => 'control-label','id'=>'idnumber-label')) !!}
                                    {!!Form::text('id_number', null, array('class' => 'form-control', 'id'=>'id_number', 'placeholder'=>'Enter ID Number','required'))!!}
                                    @if($errors->has('id_number'))
                                        {!! $errors->first('id_number', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {!!Form::label('expiry_date', 'Expiry Date (*)', array('class' => 'control-label','id'=>'expirydate-label')) !!}
                                    <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </span>
                                        {!!Form::text('expiry_date', null, array('class' => 'form-control', 'id'=>'expiry_date', 'placeholder'=>'dd/mm/yyyy', 'autocomplete' => 'off','required'))!!}

                                    </div>
                                    @if($errors->has('expiry_date'))
                                        {!! $errors->first('expiry_date', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif
                                </fieldset>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('image')) {{'has-error'}} @endif"
                                     id="image_div">

                                    {!! Form::label('image','Upload Identification (*) [10 MB max]', ['class' => 'control-label','id'=>'image-label']) !!}
                                    {!!  Form::file('image',array('required'))  !!}
                                    @if($errors->has('image'))
                                        {!! $errors->first('image', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif  </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('image1')) {{'has-error'}} @endif"
                                     id="image_div">

                                    {!! Form::label('image1','Upload Identification [10 MB max]', ['class' => 'control-label','id'=>'image-label1']) !!}
                                    {!!  Form::file('image1')  !!}
                                    @if($errors->has('image1'))
                                        {!! $errors->first('image1', '<label class="control-label"
                                                                               for="inputError">:message</label>') !!}
                                    @endif  </div>
                            </div>

                        </div>


                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <button id="submit-new-identification" class="btn btn-primary pull-right">Save</button>
        </div>
    </div>
</div>
    <script>
        var date = new Date();
        $("#expiry_date").mask('99/99/9999');
        $('input[name="expiry_date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            minDate: date
        });

        $('input[name="expiry_date"]').on('apply.daterangepicker', function (ev, picker) {
            //  $(this).val(picker.startDate.format('MM/DD/YYYY'));
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });


        $('input[name="expiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        /*$('#expiry_date').datepicker({
             format: "d-M-yyyy",
             autoclose: true,
            startDate:date
         });*/

        $("#submit-new-identification").click(function () {
                if ($("#id_type").val() === '') {
                    //  id_type_div.addClass("has-error");
                    $("#id_type").addClass("is-invalid");
                    $("#idtype-label").addClass("text-danger");
                    return false;
                } else {
                    $("#id_type").removeClass("is-invalid");
                    $("#idtype-label").removeClass("text-danger");
                }
                if ($("#issued_by").val() === '') {
                    // issued_by_div.addClass("has-error");
                    $("#issued_by").addClass("is-invalid");
                    $("#issuedby-label").addClass("text-danger");
                    return false;
                } else {
                    $("#issued_by").removeClass("is-invalid");
                    $("#issuedby-label").removeClass("text-danger");

                }
                if ($("#id_number").val() === '') {
                    $("#id_number").addClass("is-invalid");
                    $("#idnumber-label").addClass("text-danger");
                    return false;
                } else {
                    $("#id_number").removeClass("is-invalid");
                    $("#idnumber-label").removeClass("text-danger");

                }
                if ($("#expiry_date").val() === '') {
                    $("#expiry_date").addClass("is-invalid");
                    $("#expirydate-label").addClass("text-danger");
                    return false;
                } else {
                    $("#expiry_date").removeClass("is-invalid");
                    $("#expirydate-label").removeClass("text-danger");

                }
                if ($("#image").val() === '') {

                    $("#image-label").addClass("text-danger");
                    return false;
                } else {

                    $("#image-label").removeClass("text-danger");

                }
                $('#submit-new-identification').submit();

            }
        );
    </script>
<script>
    var countries = <?php echo getCountryListObject() ?>;
    var states = <?php echo AusStates() ?>;

    $(document).on("change","#sender-new-identification-form #id_type",function () {
        var select_type = $(this).val();


        var options = '';
        var options1 = '';
        $.each(countries, function (key, val) {
            // alert(key + val);
            if(val.id == 154){
                selec = 'selected';
            }else{
                selec='';
            }
            options += '<option value="'+val.name+'" '+selec+'> '+(val.name === "Nepal" ? "Nepal,MOFA" : val.name ) +'</option> <br>';


        });  $.each(states, function (key, val) {

            // alert(key + val);
            options1 += '<option value="'+val.name+'" > '+val.name +'</option> <br>';


        });

        if (select_type == 1) {
            $("#sender-new-identification-form #issued_by").html('<select class="form-control" name="issued_by">'+options+'</select>');


        } else if (select_type == 2) {
            $("#sender-new-identification-form #issued_by").html('<select class="form-control" name="issued_by">'+options1+'</select>');

        } else if (select_type == 3) {
            $("#sender-new-identification-form #issued_by").html('<select class="form-control" name="issued_by">'+options1+'</select>');
        }
    })
</script>


