@extends('layouts.default')

@section('content')

<h1 class="page-header">Alterar a senha</h1>

{!! Form::open(['route' => 'password.request', 'class' => 'form-horizontal']) !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label for="email" class="col-md-2 control-label">E-mail</label>
        <div class="col-md-4">
            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'required']) !!}

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-2 control-label">Senha</label>
        <div class="col-md-4">
            {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'required']) !!}

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="password-confirm" class="col-md-2 control-label">Confirme a senha</label>
        <div class="col-md-4">
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password-confirm', 'required']) !!}

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">
                Alterar senha
            </button>
        </div>
    </div>

{!! Form::close() !!}

@endsection