<!DOCTYPE html>
<html>
<head>



    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$application_obj->name}} | Registration Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/adminlte/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        @media (min-width: 800px) {
            .desktop-register {
                width: 800px !important;
                margin-left: -220px ;
            }
        }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div style="display: flex;justify-content: center;align-items: center;margin-top: 100px; margin-bottom: 20px">
        <img src="{{asset('assets/coreUI/img/expressewa_logo.png')}}" alt="" style="width: auto;height: 90px">
    </div>
    <div class="register-box-body desktop-register">
        <p class="login-box-msg">Register as a client</p>

        <form  method="post" action="{{route('register.handle-initial-registration')}}">
            @csrf
            <div class="form-group has-feedback @if($errors->has('email')) has-error @endif">
                <input name="email" type="email" class="form-control" value="{{old('email')}}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                    <span>
                @endif
            </div>
            <div class="form-group has-feedback  @if($errors->has('password')) has-error @endif">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                    <span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input name="password_confirmation" type="password" class="form-control" placeholder="Retype password">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                @if($errors->has('password_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    <span>
                @endif
            </div>
            @php
                $agentId = _getAgentIdFromShareableLink(request('agent'));
            @endphp
            @if($agentId)
                <div class="form-group has-feedback">
                    Agent: {{getAgentName($agentId)}}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-8" style="padding-left: 40px">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" required> I agree to the <a href="https://expressewa.com.au/terms-and-conditions/" >terms</a>
                        </label>
                    </div>
                </div>
                @if($agentId)
                <input name="agent_id" type="hidden" value="{{$agentId}}" readonly>
                @endif
                <!-- /.col -->
                <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Next</button>
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
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
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

