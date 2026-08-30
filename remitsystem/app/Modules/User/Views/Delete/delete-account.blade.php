@extends('layouts.min')
@section('title','Register')
@section('style')
    <link href="{{asset('assets/coreUI/vendors/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mx-4">
                    <div class="card-header">
                        <div style="display: flex;justify-content: center;align-items: center;margin-top: 40px; margin-bottom: 20px;">
                            <img src="{{ url('/application/'. getAppDetailsGeneral()->logo) }}" height="150" alt="logo">
                        </div>
                        <h3 class="" style="display: flex;justify-content: center; font-weight: bold;color:#004CBE;">
                            Delete your account
                        </h3>
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;  flex-direction: column;font-size: 12px">

                            Warning: This action is irreversible.

                            Once you delete your account, all your data will be permanently removed. This action cannot
                            be undone.

                            Are you sure you want to proceed?
                        </div>
                        <div>
                            <br>
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{session('error')}}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{route('delete.user.account')}}">
                            {!! Form::token() !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="form-group @if($errors->has('last_name')) {{'has-error'}} @endif">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                                @if($errors->has('email'))
                                    {!! $errors->first('email', '<label class="control-label text-danger"
                                                                            for="inputError">:message</label>') !!}
                                @endif
                            </div>
                            <div class="form-group @if($errors->has('password')) {{'has-error'}} @endif">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @if($errors->has('password'))
                                    {!! $errors->first('password', '<label class="control-label text-danger"
                                                                            for="inputError">:message</label>') !!}
                                @endif
                            </div>
                            <button type="submit" class="btn" style="background-color: #004CBE;color:#fff;">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
