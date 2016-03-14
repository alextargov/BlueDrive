@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="container-fluid">
    <div class="row login-block">
	<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
				
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open(array('url' => '/auth/register', 'class' => 'form-horizontal')) !!}

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Потребителско име</label>
                            <i class="fa fa-user"></i>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Имейл</label>
                            <i class="fa fa-envelope"></i>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Парола</label>
                            <i class="fa fa-lock"></i>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Повторете паролата</label>
                            <i class="fa fa-lock"></i>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Регистрирай ме
                                </button>
                                
                            </div>
                       </div>
                        <div class="form-group register">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ URL::to('auth/login') }}">Влизане в системата</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection