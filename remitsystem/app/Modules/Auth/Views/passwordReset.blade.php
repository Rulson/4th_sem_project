@extends('layouts.min')
@section('title', 'Login')
@section('content')
       <div class="login-box">
        <div class="login-logo">
            <a href="">Consultancy Database</a>
        </div>

        <div class="login-box-body">
            <h3>Reset your password?</h3>

            <form action="" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="confirmed_code" value="{{$token}}">

                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('name') }}">
                    @if($errors->has('email'))
                        {!! $errors->first('email', '<label for="inputError" class="control-label has-error"><i
                                    class="fa fa-times-circle-o"></i> :message</label>') !!}
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" name="new_password" class="form-control" value="{{ old('new_password') }}">
                    @if($errors->has('new_password'))
                        {!! $errors->first('new_password', '<label for="inputError" class="control-label has-error"><i
                                    class="fa fa-times-circle-o"></i> :message</label>') !!}
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Confirm password</label>
                    <input type="password" name="new_password_confirmation" class="form-control"
                           value="{{ old('new_password_confirmation') }}">
                    @if($errors->has('new_password_confirmation'))
                        {!! $errors->first('new_password_confirmation', '<label for="inputError"
                                                                                class="control-label has-error"><i
                                    class="fa fa-times-circle-o"></i> :message</label>') !!}
                    @endif
                </div>

                <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
        <div class="login-box-footer">
            <p class="text-center">
                <small>&copy; copyright 2019 | {{$application_obj->name}}</small>
            </p>
        </div>
    </div>
@stop

