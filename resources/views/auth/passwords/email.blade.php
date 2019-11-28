@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="wrapper">
                <div class="wrapper-title">
                    <h2>{{ __('content.recuperar-senha') }}</h2>
                </div>

                {!! Form::open(['route' => 'password.email']) !!}
                    <div class="form-group">
                        <label for="email">{{ __('content.username') }}</label>
                        {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-success">
                                {{  __('content.recuperar-senha') }}
                            </button>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ url('/' . app()->getLocale()) }}" class="btn btn-primary">
                                <i class="fas fa-arrow-circle-left"></i> {{ __('content.voltar') }}
                            </a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
