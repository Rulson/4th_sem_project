@extends('layouts.min')
@section('title', 'Register')
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
            <div class="col-md-6">
                <div class="card mx-4">
                    <div class="card-body p-4">

                        <h1>{{ config('layout.name') }}</h1>
                        <p class="text-muted">Register as a new client?</p>

                        <form class="" role="form" method="POST">
                    @csrf
                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                   value="{{ old('email') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                {!! $errors->first('email', '<label class="control-label"
                                                                    for="inputError">:message</label>') !!}
                            @endif
                        </div>
                        <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                {!! $errors->first('password', '<label class="control-label"
                                                                       for="inputError">:message</label>') !!}
                            @endif
                        </div>
                        <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" placeholder="Retype password"
                                   name="password_confirmation">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            @if ($errors->has('password_confirmation'))
                                {!! $errors->first('password_confirmation', '<label class="control-label"
                                                                                    for="inputError">:message</label>') !!}
                            @endif
                        </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="checkbox icheck">
                                <label class="">
                                    <div class="checkbox icheck">
                                        <label>
                                            <input type="checkbox" name="terms" id="terms"> I agree to the <a href="#" class="{{ $color }}-forgot-password">terms</a>
                                        </label>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="form-group col-xs-4">
                            <button type="submit" class="btn {{$color}}-front-btn btn-flat"
                                    id="register">Submit
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <a href="login" class="text-center {{ $color }}-forgot-password">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div>
    </div>
        </div>
    </div>

@stop
@section('page-script')
<script type="text/javascript">
    $(document).ready(function(){
        $("#terms").on('click',function(){
            if ($(this).is(":checked")) {
                $('#register').removeAttr('disabled');

            }else
            {
                $('#register').attr('disabled', 'disabled');
            }
        })
    })

</script>

@stop
