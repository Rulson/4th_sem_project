@extends('layouts.main')
@section('title', 'All Users')
@section('breadcrumb')
    @parent
    <li  class="breadcrumb-item">Users</li>
    <li  class="breadcrumb-item active">Reset Password</li>
@stop

@section('content')
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                Reset Password
            </div>
            <form method="post" action="{{route('user.passwordReset',$user->id)}}">
                {{csrf_field()}}
            <div class="card-body">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Confirm Password</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-refresh"></i> &nbsp Reset Password
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

