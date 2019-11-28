@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="wrapper">
                <div class="wrapper-title">
                    <h2>{{ __('content.registrar') }}</h2>
                </div>

                {!! Form::open(['route' => 'register', 'id' => 'formRegister']) !!}
                    <div class="form-group">
                        <label for="primeironome">{{ __('content.primeiro-nome') }}</label>
                        {!! Form::text('primeironome', null, ['class' => 'form-control', 'id' => 'primeironome', 'maxlength' => '20', 'required']) !!}
                    </div>

                    <div class="form-group">
                        <label for="sobrenome">{{ __('content.sobrenome') }}</label>
                        {!! Form::text('sobrenome', null, ['class' => 'form-control', 'id' => 'sobrenome', 'maxlength' => '130', 'required']) !!}
                    </div>

                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexo1" value="M">
                            <label class="form-check-label" for="sexo1">{{ __('content.masculino') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexo2" value="F">
                            <label class="form-check-label" for="sexo2">{{ __('content.feminino') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pais_id">{{ __('content.pais') }}</label>
                        {!! Form::select('pais_id', $paises, null, ['class' => 'form-control', 'id' => 'pais_id', 'required']) !!}
                    </div>

                    <div class="form-group">
                        <label for="timezone">{{ __('content.timezone') }}</label>
                        {!! Form::select('timezone', [], null, ['class' => 'form-control', 'id' => 'timezone', 'required']) !!}
                    </div>

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
                        {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'required']) !!}
                    </div>

                    <button type="submit" class="btn btn-success">
                        {{ __('content.registrar') }}
                    </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection


@push('js')
    <script type="text/javascript" src="{{ asset('js/timezones.full.min.js') }}"></script>
    <script type="text/javascript">
        $('#timezone').timezones();
    </script>
@endpush
