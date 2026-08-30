@extends('layouts.min')
@section('title','Login')
@section('content')
<?php
    $color = '';
    if(str_contains(request()->getHttpHost() ,'nepalpaisa')){
        $color = 'nepal-paisa';
    } elseif (str_contains(request()->getHttpHost() , 'dollarrupiya')){
        $color = 'dollar-rupiya';
    } else {
        $color = 'cash-nepal';
    }
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        @include('flash::message')
                        @if(Session::has('message'))
                            <div class="alert alert-warning">
                                <h4>Invalid Login</h4>

                                <p>{{Session::get('message')}}</p>
                            </div>
                        @endif
                        <h1>Login</h1>
                        <p class="text-muted">Sign In to your account</p>
                        <form action="" method="post">

                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-user"></i></span>
                            </div>

                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email')}}" required="required" autofocus="autofocus">

                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Password" name="password" value="" required="required">
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <button type="submit" class="btn {{$color}}-front-btn px-4">Login</button>
                            </div>
                            <div class="col-6 text-right">
                                    <a href="{{ route('password.request')}}"  class="{{ $color }}-forgot-password px-0">Forgot password?</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="card text-white bg-primary py-5">
                    <div class="card-body text-center">
                        <div>
                            <h2>Sign up</h2>
                            <p>Welcome To {{$application_obj->name}} Dashboard. Please login to continue or register by clicking register now button.</p>
                            @if(str_contains(request()->getHttpHost() ,'nepalpaisa'))
                                <a  type="button" href="{{url('register')}}" class="btn btn-warning mt-3">Register Now!</a>
                            @else
                                <a  type="button" href="{{url('register')}}" class="btn btn-primary mt-3">Register Now!</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

