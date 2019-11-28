@extends('layouts.default')

@section('content')

    <div class="wrapper">
        <div class="wrapper-title">
            <h2>{{ __('content.meu-perfil') }}</h2>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                    <i class="fas fa-user"></i> {{ __('content.meu-perfil') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">
                    <i class="fas fa-key"></i> {{ __('content.mudar-senha') }}
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="p-3">
                    {!! Form::model(auth()->user(), ['route' => 'salvar-perfil']) !!}
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
                                {!! Form::radio('sexo', 'M', null, ['class' => 'form-check-input', 'id' => 'sexo1']) !!}
                                <label class="form-check-label" for="sexo1">{{ __('content.masculino') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {!! Form::radio('sexo', 'F', null, ['class' => 'form-check-input', 'id' => 'sexo2']) !!}
                                <label class="form-check-label" for="sexo2">{{ __('content.feminino') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pais_id">{{ __('content.pais') }}</label>
                            {!! Form::select('pais_id', $paises, null, ['class' => 'form-control', 'id' => 'pais_id', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="timezone">{{ __('content.timezone') }}</label>
                            {!! Form::select('timezone', $timezones, null, ['class' => 'form-control', 'id' => 'timezone', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="locale">{{ __('content.idioma') }}</label>
                            {!! Form::select('locale', $idiomas, null, ['class' => 'form-control', 'id' => 'locale', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('content.username') }}</label>
                            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'required']) !!}
                        </div>

                        <button type="submit" class="btn btn-success">
                            {{ __('content.salvar') }}
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <div class="p-3">
                    {!! Form::open(['route' => 'salvar-senha']) !!}
                        <div class="form-group">
                            <label for="senhaatual">{{ __('content.senha-atual')}} </label>
                            {!! Form::password('senhaatual', ['class' => 'form-control', 'id' => 'senhaatual', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="novasenha">{{ __('content.nova-senha')}} </label>
                            {!! Form::password('novasenha', ['class' => 'form-control', 'id' => 'novasenha', 'required']) !!}
                        </div>

                        <div class="form-group">
                            <label for="confsenha">{{ __('content.confirme-nova-senha') }}</label>
                            {!! Form::password('confsenha', ['class' => 'form-control', 'id' => 'confsenha', 'required']) !!}
                        </div>

                        <button type="submit" class="btn btn-success">
                            {{ __('content.salvar') }}
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
@endpush
