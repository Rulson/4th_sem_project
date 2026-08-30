<!doctype html>
<html lang="en"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice Receipt</title>
    <link type="text/css" media="all" rel="stylesheet"
          href="https://almsaeedstudio.com/themes/AdminLTE/dist/css/AdminLTE.min.css">

    <style type="text/css">
        body {
            background-color: #fff;
            color: #333;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .no-border {
            border: 0;
        }

        .pull-right {
            float: right;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .panel-default {
            border-color: #ddd;
        }

        .panel {
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .panel-default > .panel-heading {
            color: #333;
            background-color: #f5f5f5;
            border-color: #ddd;
        }

        .panel-heading {
            padding: 10px 15px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            background: red;
        }

        .panel-body {
            padding: 15px;
        }

        .panel-body::after, .row::after, .col-12::after {
            clear: both;
        }

        .clearfix {
            clear: both;
        }

        .col-12 {
            width: 100%;
        }

        .col-6 {
            width: 50%;
        }

        .table {
            margin-bottom: 20px;
            max-width: 100%;
            width: 100%;
            border-collapse: collapse;
            padding: 8px;
        }

        .tab-bordered {
            border: 1px solid #b6b6b6;
        }

        .tab-bordered > tbody > tr > td, .tab-bordered > tbody > tr > th, .tab-bordered > tfoot > tr > td, .tab-bordered > tfoot > tr > th, .tab-bordered > thead > tr > td, .tab-bordered > thead > tr > th {
            border: 1px solid #b6b6b6;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            border-top: 1px solid #ddd;
            line-height: 1.42857;
            padding: 8px;
            vertical-align: top;
        }

        .col-offset-6 {
            margin-left: 50%;
        }

        .bordered {
            border: 1px solid #b6b6b6;
            border-radius: 4px;
        }

        .color-dim {
            color: #777;
        }

        .big {
            font-size: 16px;
        }

    </style>
</head><body>
<div class="container">
    <section class="invoice">
        <table class="no-border col-12">
            <tr class="invoice-info">
                <td class="invoice-col">
                    <img src=""
                         height="100px" style="max-width: 250px">
                </td>
                <td class="invoice-col text-right pull-right">
                    <h2 class="page-header">
                      #{{$items['id']}}  Invoice Receipt
                    </h2>
                </td>
            </tr>
        </table>
        <!-- info row -->
          <div class="row col-12" style="margin-left: 2px;">
            <!-- info row -->
            <table class="table no-border">
                <tr class="invoice-info">
                    <td class="invoice-col" style="width:50%;">
                        <div class="panel panel-default bordered">
                            <div class="panel-heading">
                                <strong>Sender Details</strong>
                            </div>
                            <div class="panel-body">
                                <strong>Sender Full Name</strong> : {{ getSenderName($items['sender_id'])}} <br>
                                <strong>Full Address</strong>
                                : {{getSenderDetails($items['sender_id'])->street}}, {{getSenderDetails($items['sender_id'])->suburb}},
                                {{getSenderDetails($items['sender_id'])->state}}, {{getSenderDetails($items['sender_id'])->postcode}}, {{getSenderDetails($items['sender_id'])->country}}<br/>
                                <strong>Phone/Mobile</strong>
                                :{{getSenderDetails($items['sender_id'])->number}}
                            </div>
                        </div>
                    </td>
                    <td class="invoice-col" style="width:50%;">
                        <div class="panel panel-default bordered">
                            <div class="panel-heading">
                                <strong>Receiver Details</strong>
                            </div>
                            <div class="panel-body">

                                <strong>Receiver Full Name</strong> : {{ getBeneficiaryName($items['beneficiary_id'])}} <br>
                                <strong>Full Address</strong>
                                : {{getBeneficiaryDetails($items['beneficiary_id'])->street}}, {{getBeneficiaryDetails($items['beneficiary_id'])->suburb}},
                                {{getBeneficiaryDetails($items['beneficiary_id'])->state}}, {{getBeneficiaryDetails($items['beneficiary_id'])->postcode}}, {{getBeneficiaryDetails($items['beneficiary_id'])->country}}<br/>
                                <strong>Phone/Mobile</strong>
                                :{{getBeneficiaryDetails($items['beneficiary_id'])->number}}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default bordered" style="margin:15px;">
                    <div class="panel-heading">
                        <strong>Transaction Details</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row col-12">
                            <table class="no-border">
                                <tr class="invoice-info">
                                    <td   class="invoice-col">

                                    <div class="panel-body" style=" padding: 5px;" ><strong>Sending Amount</strong> : {{ $items['sending_amount']}} <br>
                                        <strong>Total To Pay</strong>: {{ $items['total_to_pay']}} <br>
                                        <strong>Customer Rate</strong>: {{ $items['exchange_rate']}} <br>
                                        <strong>Bank Branch City </strong>: {{getBeneficiaryDetails($items['beneficiary_id'])->bsb}}<br>
                                        <strong>Bank Account No </strong>: {{getBeneficiaryDetails($items['beneficiary_id'])->account_no}}<br>
                                    </div>

                                    </td>
                                    <td class="invoice-col">
                                        <div class="panel-body" >
                                            <strong>Service Charge</strong>: {{ $items['service_charge']}} <br>
                                            <strong>Receive Amount</strong>: {{ $items['payment_amount']}} <br>
                                            <strong>Receive Method</strong>: {{ $items['payment_type']}} <br>
                                            <strong>Bank Name </strong>: {{getBeneficiaryDetails($items['beneficiary_id'])->bank_name}} <br>
                                            <strong>Bank Account Name </strong>: {{getBeneficiaryDetails($items['beneficiary_id'])->account_name}}<br>
                                            </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                             </div>
                </div>

            </div>
            <!-- /.col -->
        </div>

    </section>
</div>
</body></html>