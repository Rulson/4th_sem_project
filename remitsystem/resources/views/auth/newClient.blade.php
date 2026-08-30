<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$application_obj->name}} | Registration Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">


    <!-- Date Picker -->
    <link rel="stylesheet"
          href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box" style="margin-top: 20px!important;">
    <div class="register-logo">
        <a href="#"><strong>Money</strong>Transfer</a>
    </div>

    <div class="register-box-body" style="width: 800px!important; margin-left: -220px; ">
        <p class="login-box-msg">Please fill the details to get started using the system</p>

        <form action="#" method="post">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">General Information</h3>

                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name (*)</label>
                                        <input type="text" name="first_name" class="form-control" id="firstName"
                                               placeholder="Enter First Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name (*)</label>
                                        <input type="text" name="last_name" class="form-control" id="lastName"
                                               placeholder="Enter Last Name">
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number (*)</label>
                                        <input type="text" name="phone_number" class="form-control"
                                               id="phoneNumber"
                                               placeholder="Enter Phone Number">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Address Information</h3>

                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Street (*)</label>
                                        <input type="text" name="street" class="form-control" id="street"
                                               placeholder="Enter Street">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Suburb (*)</label>
                                        <input type="text" name="suburb" class="form-control" id="suburb"
                                               placeholder="Enter Suburb">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State (*)</label>
                                        <select class="form-control select2-state-client select2-hidden-accessible"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Post Code(*)</label>
                                        <input type="text" class="form-control" name="post_code" id="postcode"
                                               placeholder="Enter Post Code">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country (*)</label>
                                        <select class="form-control select2-country-client select2-hidden-accessible"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option selected="selected">Australia</option>
                                            <option>Russia</option>
                                            <option>Nepal</option>
                                            <option>USA</option>
                                            <option>Canada</option>
                                            <option>India</option>
                                            <option>China</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Identification Details</h3>

                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Id Type (*)</label>
                                        <select class="form-control select2-idtype-client select2-hidden-accessible"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option selected="selected">ID Type</option>
                                            <option>Driver's License</option>
                                            <option>Photo Id</option>
                                            <option>Others</option>
                                        </select></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Id Number (*)</label>
                                        <input type="text" name="id_number" class="form-control"
                                               id="idNumber"
                                               placeholder="Enter Id Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Issued By (*)</label>
                                        <input type="text" name="issued_by" class="form-control"
                                               id="issuedBy"
                                               placeholder="Enter Issued By">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expiry Date (*)</label>
                                        <input type="text" class="form-control" name="expiry_date" id="expiryDateClient"
                                               placeholder="Enter Expiry Date">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Birth (*)</label>
                                        <input type="text" placeholder="Enter Date Of Birth" class="form-control"
                                               name="dob" id="dobClient">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Upload Id (*)</label>
                                        <input type="file" id="file">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>


            <!-- /.col -->
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn-submit">Register</button>
                </div>
            </div>
            <!-- /.col -->
    </div>
    </form>
</div>
<!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>


<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>

<script>
    $(function () {

        $('#dobClient').datepicker({format: "d-M-yyyy"});
        $('#expiryDateClient').datepicker({format: "d-M-yyyy"});
        $('.select2-state-client').select2();
        $('.select2-country-client').select2();
        $('.select2-idtype-client').select2();


    });
</script>
</body>
</html>
