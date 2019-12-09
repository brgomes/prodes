@extends('layouts.default')

@section('content')

	<div class="wrapper">
		<div class="wrapper-title bg-dark">
			<div class="row">
				<h2>{{ __('content.minhas-ligas') }}</h2>
            </div>
		</div>

		{{ Form::open(['route' => 'ligas.store']) }}
			<div class="modal fade" id="novaLiga">
	  			<div class="modal-dialog modal-lg">
	    			<div class="modal-content">
	      				<div class="modal-header">
	        				<h5 class="modal-title">{{ __('content.nova-liga') }}</h5>
	        				<button type="button" class="close" data-dismiss="modal">
	          					<span aria-hidden="true">&times;</span>
	        				</button>
	      				</div>
	      				<div class="modal-body">
	    					@include('ligas._form')
	      				</div>
	      				<div class="modal-footer">
	      					<button type="submit" class="btn btn-success">{{ __('content.salvar') }}</button>
	        				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
	      				</div>
	    			</div>
	  			</div>
			</div>
		{{ Form::close() }}

		<div class="table-responsive mb-3">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.nome') }}</th>
						<th>{{ __('content.codigo') }}</th>
						<th>{{ __('content.data-inicial') }}</th>
						<th>{{ __('content.data-final') }}</th>
						<th>{{ __('content.rodadas-jogadas') }}</th>
						<th>{{ __('content.classificacao') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($ligas as $liga)
						<tr>
							<td>
								@if ($liga->admin)
									@if ($liga->liga->consolidar)
										<i class="fas fa-exclamation text-warning"></i>
									@endif
								@endif

								<a href="{{ route('ligas.show', $liga->liga->id) }}" class="link">{{ $liga->liga->nome }}</a>

								@if ($liga->admin)
									<span class="fas fa-star text-warning" title="Admin"></span>
								@endif
							</td>
							<td>{{ $liga->liga->codigo }}</td>
							<td>{{ datetime($liga->liga->datainicio, __('content.formato-data')) }}</td>
							<td>{{ datetime($liga->liga->datafim, __('content.formato-data')) }}</td>
							<td>{{ $liga->rodadasjogadas }}</td>
							<td>
								@if ($liga->posicao)
									{{ $liga->posicao }}
								@else
									-
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
	  	</div>

	  	<a href="#" class="link" data-toggle="modal" data-target="#novaLiga">
			{{ __('content.criar-nova-liga') }}
		</a>
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
