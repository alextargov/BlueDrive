@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="container-fluid container">
    <div class="row login container login-block">   

                    {!! Form::open(array('url' => '/auth/login', 'class' => 'container form-horizontal')) !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Потребителско име</label>
                            <i class="fa fa-user"></i>
                            <div class="col-md-6">
                                
                                <input type="text" id="username" class="form-control" name="username" value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Парола</label>
                            <i class="fa fa-lock"></i>
                            <div class="col-md-6">
                                
                                <input type="password" id="password" class="form-control" name="password">
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                    Влез в системата
                                </button>
                                
                            </div>
                        </div>
                        <div class="form-group register">
                            <div class="col-md-6 col-md-offset-4">
                               <a href="{{ URL::to('auth/register') }}">Не си регистриран?</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <b>Опа!</b> Има проблем с влизането в системата.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
    </div>          
</div>

@endsection