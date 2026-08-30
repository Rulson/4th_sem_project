@extends('layouts.min')
@section('title','Reset Password')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mx-4">
                    <div class="card-body p-4">

                        <h1>{{ config('layout.name') }}</h1>
                        <p class="text-muted">Set new password</p>

                        <div class="login-box">
                            <!-- /.login-logo -->
                            <div class="login-box-body">
                                <form action="{{route('activate.sender.user',$token)}}" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="confirmed_code" value="{{$token}}">

                                    <div class="form-group has-feedback">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="email" name="email" readonly class="form-control" value="{{$user->email}}">
                                        @if($errors->has('email'))
                                            {!! $errors->first('email', '<label for="inputError" class="control-label has-error"><i
                                                        class="fa fa-times-circle-o"></i> :message</label>') !!}
                                        @endif
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="exampleInputEmail1">New Password</label>
                                        <input type="password" name="new_password" class="form-control" value="{{ old('new_password') }}">
                                        @if($errors->has('new_password'))
                                            {!! $errors->first('new_password', '<label for="inputError" class="control-label text-danger has-error"><i
                                                        class="fa fa-times-circle-o"></i> :message</label>') !!}
                                        @endif
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="exampleInputEmail1">Confirm password</label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                               value="{{ old('new_password_confirmation') }}">
                                        @if($errors->has('new_password_confirmation'))
                                            {!! $errors->first('new_password_confirmation', '<label for="inputError"
                                                                                                    class="control-label text-danger has-error"><i
                                                        class="fa fa-times-circle-o"></i> :message</label>') !!}
                                        @endif
                                    </div>

                                    <div class="box-footer">
                                        <button class="btn btn-primary btn-block btn-flat" type="submit">Set Password</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <p class="text-center">
                                    <small>&copy; copyright {{date('Y')}} | {{getAppDetailsGeneral()->name}}</small>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


