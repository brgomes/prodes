@extends('layouts.default')

@section('content')

	<div class="wrapper">
		<div class="wrapper-title">
			<div class="row">
                <div class="col-sm-6">
					<h2>{{ __('content.menu-ligas') }}</h2>
				</div>
				<div class="col-sm-6">
					<a href="#" class="btn btn-success" data-toggle="modal">
						<i class="fas fa-plus-circle"></i> {{ __('content.nova-liga') }}
					</a>
				</div>
            </div>
		</div>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.nome') }}</th>
						<th>{{ __('content.data-inicial') }}</th>
						<th>{{ __('content.data-final') }}</th>
						<th>{{ __('content.rodadas') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($ligas as $liga)
						<tr>
							<td>
								<span class="{{ $usuario->bandeira }}" title="{{ $usuario->pais->nome }}"></span>
								<a href="#">{{ $usuario->primeironome . ' ' . $usuario->sobrenome }}</a>

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
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
