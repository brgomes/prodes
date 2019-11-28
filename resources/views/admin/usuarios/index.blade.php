@extends('layouts.default')

@section('content')

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{{ __('content.nome') }}</th>
					<th>{{ __('content.email') }}</th>
					<th>{{ __('content.idioma') }}</th>
					<th>{{ __('content.timezone') }}</th>
					<th>{{ __('content.ultimo-acesso') }}</th>
					<th>{{ __('content.created-at') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($usuarios as $usuario)
					<tr>
						<td>
							<span class="flag-icon flag-icon-{{ mb_strtolower($usuario->pais->iso3) }}" title="{{ $usuario->pais->nomePT }}"></span>
							{{ $usuario->primeironome . ' ' . $usuario->sobrenome }}
						</td>
						<td>{{ $usuario->email }}</td>
						<td>{{ $usuario->locale }}</td>
						<td>{{ $usuario->timezone }}</td>
						<td>{{ $usuario->ultimoacesso }}</td>
						<td>{{ $usuario->created_at }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
  	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
