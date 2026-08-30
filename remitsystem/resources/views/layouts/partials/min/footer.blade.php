<script src="{{asset('assets/coreUI/vendors/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/popper.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/moment.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/select2.min.js')}}"></script>


<script>
    /*$(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });*/
    $(function () {
      /*  var date = new Date();
        $("#dob1").datepicker({
            autoclose: true,
            format: 'dd-M-yyyy',
            endDate: date
        });*/
        /*$("#expiryDateClient").datepicker({
            autoclose: true,
            format: 'dd-M-yyyy',
            startDate: date
        });*/
      /*  $('.select2-state-client').select2();
        $('.select2-country-client').select2();
        $('.select2-idtype-client').select2();*/

        $("body").on('click','#registerClient',function(){
            if($("#firstName").val() != '' && $("#lastName").val() != '' && $("#phoneNumber").val() != '' && $("#dob1").val() != '' && $("#street").val() != '' && $("#suburb").val() != '' && $("#state").val() != '' && $("#postcode").val() != '' && $("#country").val() != '' && $("#id_type").val() != '' && $("#idNumber").val() != '' && $("#issuedBy").val() != '' && $("#expiryDateClient").val() != '' && $("#file").val() != ''){
                var phn_num = $("#phoneNumber").val();
                var label = $('#phoneNumber').closest('div.col-md-6').find('label.text-danger');
                label.remove();
                if(/^(?:\+?61|0)4([0-9]{8})$/.test(phn_num)){
                    document.getElementById("newClientSubmit").submit();
                    document.getElementById("registerClient").disabled=true;
                    document.getElementById("registerClient").value='Submitting.Please wait...';
                }
                else{
                    $("#phoneNumber").parent().after('<label class="error text-danger">Please Enter Valid Number.</label>');
                    return false;
                }
            }else{
                document.getElementById("newClientSubmit").submit();
            }
        });
    });
</script>
<script>

        var date = new Date();
        var dob = new Date();
        dob.setTime(dob.getTime()-(16*365*24*60*60*1000));
        $('input[name="dob"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            maxDate: dob
        });
        $('input[name="dob"]').on('apply.daterangepicker', function (ev, picker) {

            $(this).val(picker.startDate.format('DD/MM/YYYY'));
            // $(this).val(picker.startDate.format('DD-MMM-YYYY'));
        });

        $('input[name="dob"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
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
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
            // $(this).val(picker.startDate.format('DD-MMM-YYYY'));
        });

        $('input[name="expiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

</script>
@yield('page-script')
