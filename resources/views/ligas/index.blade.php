@extends('layouts.default')

@section('content')

	<div class="wrapper">
		<div class="wrapper-title">
			<div class="row">
                <div class="col-sm-6">
					<h2>{{ __('content.minhas-ligas') }}</h2>
				</div>
				<div class="col-sm-6">
					<a href="#" class="btn btn-success" data-toggle="modal" data-target="#novaLiga">
						<i class="fas fa-plus-circle"></i> {{ __('content.nova-liga') }}
					</a>
				</div>
            </div>
		</div>

		{{ Form::open(['route' => 'ligas.store']) }}
			<div class="modal fade" id="novaLiga">
	  			<div class="modal-dialog">
	    			<div class="modal-content">
	      				<div class="modal-header">
	        				<h5 class="modal-title" id="exampleModalLabel">{{ __('content.nova-liga') }}</h5>
	        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.nome') }}</th>
						<th>{{ __('content.data-inicial') }}</th>
						<th>{{ __('content.data-final') }}</th>
						<th>{{ __('content.rodadas-jogadas') }}</th>
						<th>{{ __('content.classificacao') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($ligas as $liga)
						<tr>
							<td>
								<a href="#">{{ $liga->liga->nome }}</a>

								@if ($liga->admin)
									<span class="fas fa-star text-warning" title="Admin"></span>
								@endif
							</td>
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
							<td>
								<a href="#" data-toggle="modal"><i class="fas fa-pencil-alt text-success" title="{{ __('content.editar') }}"></i></a>
							</td>
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
