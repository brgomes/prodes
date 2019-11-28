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
							<span class="{{ $usuario->bandeira }}" title="{{ $usuario->pais->nome }}"></span>
							{{ $usuario->primeironome . ' ' . $usuario->sobrenome }}

							@if ($usuario->superadmin)
								<span class="fas fa-star text-warning" title="Superadmin"></span>
							@elseif ($usuario->admin)
								<span class="far fa-star text-warning" title="Admin"></span>
							@endif
						</td>
						<td>{{ $usuario->email }}</td>
						<td>{{ $usuario->locale }}</td>
						<td>{{ $usuario->timezone }}</td>
						<td>{{ datetime($usuario->ultimoacesso,  __('content.formato-datahora-completo')) }}</td>
						<td>{{ datetime($usuario->created_at, __('content.formato-datahora-completo')) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
  	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
