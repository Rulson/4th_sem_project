<script>

    var AppUrl = "{{url('/')}}";
    var txnId = "";

    function loadAllComments(txnId) {
        $.ajax({
            url: AppUrl + '/fetchcomments',
            method: 'GET',
            data: {Id: txnId},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#comments').html(data.comment);
                setTimeout(function () {
                    $('.callout').remove()
                }, 2500);
            }
        });
    }

    $(document).on('input propertychange change', '#staff_note_textarea', function () {
        var txn = $(this).attr('data-txn');
        var formData = $("#add-staffnote-form-" + txn).serialize();

        var url = '/transaction/admin-staff-notes/' + txn;
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            encode: true
        }).done(function (result) {


        });
    });

    $("#note-submit-navbar").on("click", function (event) {
        var url = '/store-note-navbar';
        var formData = CKEDITOR.instances.editor1.getData();
        var token = '{{csrf_token()}}';

        $.ajax({
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            url: url,
            data: {notes: formData},
            dataType: 'json',
            encode: true
        }).done(function (result) {
            if (result == 1) {
                $('.header-notes-msg').after(notify('success', 'Note added successfully!'));
                setTimeout(function () {
                    $('.callout').remove();
                }, 2500);
            } else {
                $('.header-notes-msg').after(notify('danger', 'Something went wrong!'));
            }
        })
    });

    function notify(type, text) {
        return '<div class="callout callout-' + type + '"><p>' + text + '</p></div>';
    }

    $(document).on("submit", "#comment-form", function (event) {
        txnId = $(this).find('#save-button').attr('data-transactionid');
        var formData = $(this).serialize();
        var url = AppUrl + '/transaction/comment/' + txnId;
        $('#save-button').attr('disabled', 'disabled');
        // process the form
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            encode: true
        }).done(function (result) {
            if (result.status == 1) {
                $('#save-button').removeAttr('disabled');
                $('#comment-value').val('');
                $("input[name='admin']").prop('checked', false);
                $("input[name='agent_user_id']").prop('checked', false);
                $("input[name='client_user_id']").prop('checked', false);
                loadAllComments(txnId);
            }
            setTimeout(function () {
                $('.callout').remove()
            }, 2500);
        });
        event.preventDefault();
    });
    $('#recentTransactionUnconfirmed').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "pageLength": 50,
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionUnconfirmed').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all7').not(this).prop('checked', false);

    });

    $('#recentTransactionConfirmed').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionConfirmed').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all1').not(this).prop('checked', false);

    });
    $('#recentTransactionSendForCollection').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionSendForCollection').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all2').not(this).prop('checked', false);
        $('#check-all3').not(this).prop('checked', false);

    });
    $('#recentTransactionPaymentInProgress').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionPaymentInProgress').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all4').not(this).prop('checked', false);

    });
    $('#recentTransactionOnhold').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionOnhold').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all6').not(this).prop('checked', false);
    });
    $('#recentTransactionCancelled').DataTable({
        language: {
            zeroRecords: "No records found",
            "scrollX": true,
        },
        'order': [[1, 'desc']],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ]
    });
    $('#recentTransactionOnhold').on('page.dt', function () {
        $('input.checks').not(this).prop('checked', false);
        $('#check-all5').not(this).prop('checked', false);
    });
    $('#agentPayments').DataTable({
        language: {
            zeroRecords: "No records found",
            'order': [1, 'desc']
        }
    });
    $('#distributorBalance').DataTable({
        language: {
            zeroRecords: "No records found",
            'order': [1, 'desc']
        }
    });
    $('#date-joined').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'MM/DD/YYYY'
        }
    });

    $('#date-joined').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#date-joined').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });


</script>

<script>
    $(function () {
        $('#exampleTransaction').on('page.dt', function () {
            $('input.checks').not(this).prop('checked', false);
            $('#check-all').not(this).prop('checked', false);
        });
        $('#exampleTransaction').DataTable({
            'scrollX': true,
            'order': [[1, 'desc']],
            "pageLength": 50,
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0]}
            ]
        });
        $('#exampleTransactionSearch').on('page.dt', function () {
            $('input.checks').not(this).prop('checked', false);
            $('#check-all').not(this).prop('checked', false);
        });
        $('#exampleTransactionSearch').DataTable({
            'scrollX': true,
            'order': [[1, 'desc']],
            "pageLength": 50,
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0]}
            ],
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                'copy', 'csv', 'excel', 'print'
            ],
        });
        $('#changeStatusButton').on('click', function () {
            changeStatus('statusChangeSelect2');
        });
        $('#changeDistributorButton').on('click', function () {
            assignDistributor('DistributorAssignSelect2');
        });
        $('#changeStatusButton5').on('click', function () {
            changeStatus('statusChangeCancelled');
        });
        $('#changeDistributorButton5').on('click', function () {
            assignDistributor('DistributorAssignCancelled');
        });
        $('#changeStatusButton4').on('click', function () {
            changeStatus('statusChangeConfirmed');
        });
        $('#changeDistributorButton4').on('click', function () {
            assignDistributor('DistributorAssignConfirmed');
        });
        $('#changeStatusButton2').on('click', function () {
            changeStatus('statusChangePaymentInProgress');
        });
        $('#changeDistributorButton2').on('click', function () {
            assignDistributor('DistributorAssignPaymentInProgress');
        });
        $('#changeStatusButton3').on('click', function () {
            changeStatus('statusChangeSendForCollection');
        });
        $('#changeDistributorButton3').on('click', function () {
            assignDistributor('DistributorAssignSendForCollection');
        });
        $('#changeStatus1').on('click', function () {
            changeStatus('statusChangeOnHold');
        });
        $('#changeDistributorButton1').on('click', function () {
            assignDistributor('DistributorAssignOnHold');
        });
        $('#changeStaffButton').on('click', function () {
            assignStaff('StaffAssignSelect2');
        });
        $('#changeStaffButtonConfirmed').on('click', function () {
            assignStaff('StaffAssignConfirmed');
        });
        $('#changeStaffButtonCancelled').on('click', function () {
            assignStaff('StaffAssignCancelled');
        });
        $('#changeStaffButtonSendForCollection').on('click', function () {
            assignStaff('StaffAssignSendForCollection');
        });
        $('#changeStaffButtonPaymentInProgress').on('click', function () {
            assignStaff('StaffAssignPaymentInProgress');
        });
        $('#changeStaffButtonDelivered').on('click', function () {
            assignStaff('StaffAssignDelivered');
        });
        $('#changeStaffButtonCancelled').on('click', function () {
            assignStaff('StaffAssignCancelled');
        });
        $('#changeStaffButtonOnHold').on('click', function () {
            assignStaff('StaffAssignOnHold');
        });
        $("agent-rate-update").on("click", function () {
            // /*{{route('updateAgentRate.modal')}}*/
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            console.log();
            if (formData != '') {
                var str = [];
                for (var p in formData)
                    if (formData.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(formData[p]));
                    }
                var queryStringIds = str.join("&");
                //    window.location = '{{ route('transactions.export')}}' + '?' + queryStringIds;
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
            }

        });
        $('#excel-export1').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export1.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });

            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });
        $('#excel-export2').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export2",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export2.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });

            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });
        $('#excel-export3').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export3",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export3.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });
        $('#excel-export4').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export4",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export4.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });

        $('#austrac-export').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/austrac-export",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.exportAustrac.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
            }
        });
        $('#excel-export5').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export5",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export5.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });

        $('#excel-export6').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export6",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export6.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });

        $('#excel-export7').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export7",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export7.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });

        $('#excel-export8').on('click', function (e) {
            e.preventDefault();
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var token = '<?php echo e(csrf_token()); ?>';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    type: "post",
                    data: {selected_id: formData, token: token},
                    url: "/transactions/export8",

                    success: function (result) {
                        if (result == 'success') {
                            //alert('success');
                            window.location = '{{ route('transactions.export8.download')}}';
                        } else {
                            setTimeout(function () {
                                // Runs 1 second (1000 ms) after the last change
                                toastr.error('Oops, something went wrong !!!', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            });
                        }
                    }
                });
            }
            else {
                setTimeout(function () {
                    // Runs 1 second (1000 ms) after the last change
                    toastr.error('Please check at least one transaction !!!', {
                        closeButton: true,
                        progressBar: true,
                    });
                });
                //  }, 5000);
                // swal("Please checked Transactions Id!!");
            }
        });

        function assignDistributor(id) {

            var e = document.getElementById(id);
            var valueCompanyId = e.options[e.selectedIndex].value;
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var str = [];
                for (var p in formData)
                    if (formData.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(formData[p]));
                    }
                var queryStringIds = str.join("&");
                window.location = '{{ url("transactions/assignDistributorMultiple") }}' + "/" + valueCompanyId + '?' + queryStringIds;
            }
            else {
                swal("Oops", "Please check at least one transaction to continue this action!!", "error");
            }
        }

        function assignStaff(id) {
            var e = document.getElementById(id);
            var valueUserId = e.options[e.selectedIndex].value;
            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });

            if (formData != '') {
                var str = [];
                for (var p in formData)
                    if (formData.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(formData[p]));
                    }
                var queryStringIds = str.join("&");
                window.location = '{{ url("transactions/assignStaffMultiple") }}' + "/" + valueUserId + '?' + queryStringIds;
            }
            else {
                swal("Oops", "Please check at least one transaction to continue this action!!", "error");
            }

        }

        function changeStatus(id) {
            var e = document.getElementById(id);
            var valueStatus = e.options[e.selectedIndex].value;

            var formData = [];
            $.each($('input.checks:checked'), function () {
                var id = $(this).val();
                formData.push(id);
            });
            if (formData != '') {
                var str = [];
                for (var p in formData)
                    if (formData.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(formData[p]));
                    }
                var queryStringIds = str.join("&");
                window.location = '{{ url("transactions/changeStatusMultiple") }}' + "/" + valueStatus + '?' + queryStringIds;
            }
            else {
                swal("Oops", "Please check at least one transaction to continue this action!!", "error");
            }
        }
    });
    $(document).on('click', '#check-all', function (e) {
        $('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all1', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all2', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all3', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all4', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all5', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all6', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '#check-all7', function (e) {
        $(this).closest('table').find('input.checks').not(this).prop('checked', this.checked);
    });

    var query = 1;

    if (location.pathname == "/transactions/transaction-dashboard") {
        fetchLineChartData();
        // fetchAgentLineChartData();
        fetchTransactionByUsersChartData();
        fetchTransactionByDistributorsChartData();
        fetchTransactionByAgentsChartData();
    }

    if(location.pathname == "/dashboard"){
        fetchExchangeRateData();
    }

    if (location.pathname == "/agents/dashboard") {
        fetchTransactionByAgentsChartData();
        fetchAgentLineChartData();
    }
    /*  if(location.pathname == "/transactions/transaction-dashboard"){
          fetchAgentBarChartData();
      }*/
    $('#data-as-selected').on('change', function () {

        query = $(this).val();
        fetchLineChartData();
    });
    $('#data-as-selected-user').on('change', function () {
        query = $(this).val();
        fetchTransactionByUsersChartData();
    });
    $('#data-as-selected1').on('change', function () {
        query = $(this).val();
        fetchAgentLineChartData();
    });

    function fetchLineChartData() {
        url = '/fetchLineChartData';
        $.ajax({
            url: url,
            method: 'GET',
            data: {query: query},
            dataType: 'json',
            success: function (data) {
                var value1 = [];
                var value2 = [];
                var value3 = [];
                var keys1 = Object.keys(data.sendingAmountPerMonth);
                for (var counter = 0; counter < keys1.length; counter++) {
                    value1 [counter] = data.sendingAmountPerMonth[keys1[counter]]
                }
                var keys2 = Object.keys(data.paymentAmountPerMonth);
                for (var counter = 0; counter < keys2.length; counter++) {
                    value2 [counter] = data.paymentAmountPerMonth[keys2[counter]]
                }
                var keys3 = Object.keys(data.noOfTransactions);
                for (var counter = 0; counter < keys3.length; counter++) {
                    value3 [counter] = data.noOfTransactions[keys3[counter]]
                }
                transaction.data.labels = keys1;
                transaction.data.datasets[0].data = value1;
                transaction.data.datasets[1].data = value2;
                transaction.data.datasets[2].data = value3;
                transaction.update();
            }
        });

    }
    function fetchExchangeRateData(){
        url = '/fetchExchangeRate';
        $.ajax({
            url: url,
            method: 'GET',
            data: {query: query},
            dataType: 'json',
            success: function (data) {
                var value1 = [];
                var keys1 = Object.keys(data.exchange_rate);
                for (var counter = 0; counter < keys1.length; counter++) {
                    value1 [counter] = data.exchange_rate[keys1[counter]]
                }
                exchange.data.labels = keys1;
                exchange.data.datasets[0].data = value1;

                exchange.update();
            }
        });
    }

    function fetchAgentLineChartData() {
        url = '/fetchAgentLineChartData';
        $.ajax({
            url: url,
            method: 'GET',
            data: {query: query},
            dataType: 'json',
            success: function (data) {
                var value1 = [];
                var value2 = [];
                var keys1 = Object.keys(data.sendingAmountPerMonth);
                for (var counter = 0; counter < keys1.length; counter++) {
                    value1 [counter] = data.sendingAmountPerMonth[keys1[counter]]
                }
                var keys2 = Object.keys(data.paymentAmountPerMonth);
                for (var counter = 0; counter < keys2.length; counter++) {
                    value2 [counter] = data.paymentAmountPerMonth[keys2[counter]]
                }
                transactionAgentDashboard.data.labels = keys1;
                transactionAgentDashboard.data.datasets[0].data = value1;
                transactionAgentDashboard.data.datasets[1].data = value2;
                transactionAgentDashboard.update();
            }
        });

    }


    function fetchTransactionByUsersChartData() {
        url = '/fetchTransactionByUsersChartData';
        $.ajax({
            url: url,
            method: 'GET',
            data: {query: query},
            dataType: 'json',
            success: function (data) {
                addData(transactionDoughNut, data.label, data.value);
            }
        });

    }

    function fetchTransactionByDistributorsChartData() {
        url = '/fetchTransactionByDistributorsChartData';
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var value = [];
                var label = [];
                var keys = Object.keys(data);
                for (var counter = 0; counter < keys.length; counter++) {
                    value [counter] = data[keys[counter]]

                    label [counter] = keys[counter]
                }
                addData(transactionDistributor, label, value);
            }
        });

    }

    function fetchTransactionByAgentsChartData() {
        url = '/fetchTransactionByAgentsChartData';
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var value = [];
                var label = [];
                var keys = Object.keys(data);
                for (var counter = 0; counter < keys.length; counter++) {
                    value [counter] = data[keys[counter]];

                    label [counter] = keys[counter];
                }
                addData(transactionAgent, label, value);
            }
        });
    }

</script>

