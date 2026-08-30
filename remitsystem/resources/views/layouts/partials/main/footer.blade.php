<footer class="app-footer">
    <span><a href="http://coreui.io/pro/">{{getAppDetailsGeneral()->name}} </a> © {{ date('Y') }}.</span>
</footer>
<script src="{{asset('assets/coreUI/vendors/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/popper.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/pace.min.js')}}"></script>

<!-- Plugins and scripts required by all views -->
<script src="{{asset('assets/coreUI/vendors/js/Chart.min.js')}}"></script>

<!-- CoreUI Pro main scripts -->

<script src="{{asset('assets/coreUI/js/app.js')}}"></script>

<!-- Plugins and scripts required by this views -->
<script src="{{asset('assets/coreUI/vendors/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/coreUI/js/views/datatables.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/gauge.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/moment.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/select2.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/daterangepicker.min.js')}}"></script>
<script src="{{asset('assets/coreUI/js/views/text-editors.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/spin.min.js')}}"></script>
<script src="{{asset('assets/coreUI/vendors/js/ladda.min.js')}}"></script>
<!-- Custom scripts required by this view -->
<script src="{{asset('assets/coreUI/js/views/main.js')}}"></script>
<script src="{{asset('assets/coreUI/js/ckeditor/ckeditor.js')}}"></script>

<script>
    $(document).on("click", "a[data-url]", function () {
        var url = $(this).attr("data-url");
        $(".load-by-url").load(url);

        return false;
    });

    $('body').on('hidden.bs.modal', '.sendingmodal', function () {
        $('.load-by-url').html('Loading...');
    });
    $(".select2").select2({
        theme: "bootstrap"
    });
</script>

<script>


    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
    @endif

    $('.read-note').on('click', function (event) {

        if ($(this).is(":checked")) {
            var confirmed = confirm('Are you sure you want to mark the notification as read?');
            if (!confirmed) {
                setTimeout(function () {
                    $('.read-note').prop('checked', false);
                }, 0.5);
                return false;
            }
            var id = $(this).attr('id');
            var parentLi = $(this).closest('span');
            $.ajax({
                url: '/notification/read/' + id,
                type: 'get',
                success: function (response) {
                    if (response.status == 1) {
                        parentLi.slideUp('slow');
                        var count = parseInt($('.count-notification').first().text());
                        $('.count-notification').html((count - 1));
                        $('.count-notification-msg').html('You have ' + (count - 1) + ' notification(s)');
                    }
                }
            });
        }
    });
    $('.read-note-click').on('click', function (event) {
        var id = $(this).attr('id');
        var transactionId = $(this).attr('transactionid');
        $.ajax({
            url: '/transactions/show/' + id,
            type: 'get',
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = '/transactions/show/' + transactionId;
                }
            }
        });
    });


</script>

<script>
    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('sender.markNotification') }}", {
            method: 'get',
            data: {
                id
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            if ($(this).is(":checked")) {
                var confirmed = confirm('Are you sure you want to mark the notification as read?');
                if (!confirmed) {
                    setTimeout(function () {
                        $('.mark-as-read').prop('checked', false);
                    }, 0.5);
                    return false;
                }

                var parentLi = $(this).closest('span');
            }
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                parentLi.slideUp('slow');
                parentLi.remove();
                var count = parseInt($('.count-transaction-notification').first().text());
                $('.count-transaction-notification').html((count - 1));
                $('.count-transaction-notification-msg').html('You have ' + (count - 1) + ' notification(s)');
            });
        });
        $('#mark-all').click(function() {
            var confirmed = confirm('Are you sure you want to mark all notification as read?');
            if (!confirmed) {
                setTimeout(function () {
                    $('.mark-as-read').prop('checked', false);
                }, 0.5);
                return false;
            }

            let request = sendMarkRequest();
            request.done(() => {
                var $all_spans = $(this).parents('div.transaction-not-class').find('span.no-bg');
                var all_spans_length = $(this).parents('div.transaction-not-class').find('span.no-bg').length;

                var count = parseInt($('.count-transaction-notification').first().text());
                $('.count-transaction-notification').html((count - all_spans_length));
                $('.count-transaction-notification-msg').html('You have ' + (count - all_spans_length) + ' notification(s)');
                $all_spans.remove();
                $(this).parents('div.dropdown-header').remove();
            })
        });
    });
</script>
