@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="wrapper">
				<div class="wrapper-title">
	  				<h2>{{ __('content.login') }}</h2>
	  			</div>

				{!! Form::open(['route' => 'login', 'class' => 'form-signin', 'id' => 'form']) !!}
					<label for="email" class="sr-only">{{ __('content.username') }}</label>
		  			<input type="email" name="email" id="email" class="form-control" placeholder="{{ __('content.username') }}" required autofocus>
		  			<label for="password" class="sr-only">{{ __('content.senha') }}</label>
		  			<input type="password" name="password" id="password" class="form-control" placeholder="{{ __('content.senha') }}" required>
		  			<input type="hidden" id="lang" value="">
		  			<div class="checkbox mb-3">
		    			<label>
		      				<input type="checkbox" value="remember"> {{ __('content.lembrar-login') }}
		    			</label>
		  			</div>
		  			<a href="{{ route('password.request') }}">{{ __('content.esqueceu-senha') }}</a>
		  			<br /><br />
		  			<button class="btn btn-lg btn-primary btn-block" type="submit">{{ __('content.entrar') }}</button>
		  			<a class="btn btn-lg btn-success btn-block text-white" href="{{ route('register') }}">{{ __('content.registrar') }}</a>
				{!! Form::close() !!}
		  	</div>
		</div>
	</div>

@stop
