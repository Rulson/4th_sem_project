@extends('layouts.min')
@section('title','Reset Password')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mx-4">
                    <div class="card-body p-4">

                        <h1>{{ config('layout.name') }}</h1>
                        <p class="text-muted">Send Activation Email</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="login-box">


                            <!-- /.login-logo -->
                            <div class="login-box-body">
                                <p class="login-box-msg"></p>


                                <p class="login-box-msg">To resend the activation email, enter the email address that you registered in the system.</p>

                                @include('flash::message')
                                <form method="POST" action="{{ route('resend-email') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                                    <div class="form-group has-feedback @if($errors->has('email')) {{'has-error'}} @endif">
                                        <input type="text" class="form-control" name="email" placeholder="Email"
                                               value="{{old('email')}}"/>
                                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                        @if($errors->has('email'))
                                            {!! $errors->first('email', '<label class="control-label" for="inputError">:message</label>
                                            ') !!}
                                        @endif

                                    </div>
                                    <button type="submit" class="btn btn-block btn-primary">Send Activation Email</button>
                                    @csrf


                                </form>
                            </div>
                            <div class="card-footer">
                                <p class="text-center">
                                    <small>&copy; copyright 2019 | {{$application_obj->name}}</small>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
