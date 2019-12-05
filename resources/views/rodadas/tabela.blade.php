@extends('layouts.default')

@section('content')

	<div class="wrapper">
		<div class="wrapper-title bg-dark">
			<div class="row text-center">
				<h2>{{ $rodada->liga->nome }} - {{ __('content.rodada') }} {{ $rodada->numero }}</h2>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.jogador') }}</th>
						
						@foreach ($rodada->partidas as $partida)
							<th>{{ $partida->sigla }}</th>
						@endforeach

						<th>{{ __('content.total') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						
					</tr>
				</tbody>
			</table>
	  	</div>
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
