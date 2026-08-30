@extends('layouts.main')
@section('title', 'All Users')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Update Credential</li>
@stop

@section('content')


            <form method="post" action="{{route('user.credential.edit.store',$user->id)}}">
                {{csrf_field()}}

                <div class="animated fadeIn">
                    <div class="col-sm-12">

                        <div class="card">
                            <div class="card-header">
                                <strong>Update Credential</strong>

                            </div>
                            <div class="card-body">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Email</label>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="{{$user->email}}" autocomplete="off" readonly>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Password</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" autocomplete="off">

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
                                <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i> &nbsp Update Credential
                                </button>
                                </div>
                </div>
                        </div>
                    </div>
                </div>


            </form>
@endsection

@section('page-script')

@stop