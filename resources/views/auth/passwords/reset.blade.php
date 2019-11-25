@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3>{{ __('content.redefinir-senha') }}</h3>

            {!! Form::open(['route' => 'password.request']) !!}
                {!! Form::hidden('token', $token) !!}

                <div class="form-group">
                    <label for="email">{{ __('content.username') }}</label>
                    {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'required']) !!}
                </div>

                <div class="form-group">
                    <label for="password">{{ __('content.senha')}} </label>
                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'required']) !!}
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('content.confirme-senha') }}</label>
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password-confirm', 'required']) !!}
                </div>

                <button type="submit" class="btn btn-success">
                    {{ __('content.redefinir-senha') }}
                </button>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
