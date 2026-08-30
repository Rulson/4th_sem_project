<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">
    <title>{{ config('app.name') }}| Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}"> <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js')}} IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js')}} doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">

<div style="display: flex;justify-content: center;align-items: center;margin-top: 100px">
    <img src="{{asset('assets/coreUI/img/expressewa_logo.png')}}" alt="" style="width: auto;height: 90px">
</div>
<div class="login-box" style="margin-top: 30px">
{{--    <div class="login-logo">--}}
{{--        <a href=#"><b>Money</b>Transfer</a>--}}
{{--    </div>--}}
    <!-- /.login-logo -->
    <div class="login-box-body" style="margin-top:0;padding-top: 20px ">
        <p class="login-box-msg">Sign in to start your session</p>
        @if(session('account_deleted'))
            <div class="alert alert-success" style="margin-bottom: 0px">
               <h4>
                   Your account has been deleted successfully.
               </h4>
            </div>
            <br>
        @endif

        <form method="post" action="{{route('login')}}">
            {!!csrf_field()!!}
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" name="password" placeholder="Password" value="{{ old('password') }}" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="{{url('register')}}" class="text-center"><i class="fa fa-lock"> Register</i></a>
                    <a href="{{route("email.request")}}" class="text-center pull-right">Forgot your password?</a>

                </div>
            </div>
            <br>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat pull-right">Sign In</button>
                </div>

                <!-- /.col -->
            </div>
        </form>

        <!-- <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a> -->

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{asset('/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>

