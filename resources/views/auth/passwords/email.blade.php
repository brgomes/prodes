@extends('layouts.default')

@section('content')

<h1 class="page-header">Recuperar senha</h1>

{!! Form::open(['route' => 'password.email', 'class' => 'form-horizontal']) !!}

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="form-group">
        <label for="email" class="col-md-2 control-label">E-mail</label>
        <div class="col-sm-4">
            {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">
                Enviar link recuperação de senha
            </button>
        </div>
    </div>

{!! Form::close() !!}

@endsection