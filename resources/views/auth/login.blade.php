@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-md-12">
	    	<form class="form-signin">
	  			<h1 class="h3 mb-3 font-weight-normal">{{ __('content.login') }}</h1>
				<label for="email" class="sr-only">{{ __('content.username') }}</label>
	  			<input type="email" id="email" class="form-control" placeholder="{{ __('content.username') }}" required autofocus>
	  			<label for="password" class="sr-only">{{ __('content.senha') }}</label>
	  			<input type="password" id="password" class="form-control" placeholder="{{ __('content.senha') }}" required>
	  			<div class="checkbox mb-3">
	    			<label>
	      				<input type="checkbox" value="remember-me"> {{ __('content.lembrar-login') }}
	    			</label>
	  			</div>
	  			<a href="#">{{ __('content.esqueceu-senha') }}</a>
	  			<br /><br />
	  			<button class="btn btn-lg btn-primary btn-block" type="submit">{{ __('content.entrar') }}</button>
	  			<a class="btn btn-lg btn-success btn-block text-white" href="{{ route('register') }}">{{ __('content.registrar') }}</a>
			</form>
		</div>
  	</div>

@stop
