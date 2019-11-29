@extends('layouts.default')

@section('content')

	<h2>{{ $liga->nome }}</h2>



	<div class="wrapper">
		<div class="wrapper-title">
			<div class="row">
				<h2>{{ __('content.classificacao') }}</h2>
            </div>
		</div>
	  	<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.nome') }}</th>
						<th>{{ __('content.codigo') }}</th>
						<th>{{ __('content.data-inicial') }}</th>
						<th>{{ __('content.data-final') }}</th>
						<th>{{ __('content.rodadas-jogadas') }}</th>
						<th>{{ __('content.classificacao') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($liga->rodadas as $liga)
						<tr>
							<td>
								<a href="{{ route('ligas.show', $liga->id) }}">{{ $liga->liga->nome }}</a>

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
							<td>
								<a href="#" data-toggle="modal" data-target="#modalEditarLiga{{ $liga->liga->id }}"><i class="fas fa-pencil-alt text-success" title="{{ __('content.editar') }}"></i></a>
								@include('ligas.edit', ['liga' => $liga->liga, 'name' => 'modalEditarLiga'])
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
	  	</div>
	</div>




	<div class="wrapper mt-5">
		<div class="wrapper-title">
			<div class="row">
                <div class="col-sm-6">
					<h2>{{ __('content.rodadas') }}</h2>
				</div>
				<div class="col-sm-6">

					@if ($classificacao->admin)
						<a href="#" class="btn btn-success" data-toggle="modal" data-target="#novaRodada">
							<i class="fas fa-plus-circle"></i> {{ __('content.nova-rodada') }}
						</a>
					@endif
				</div>
            </div>
		</div>

		@if ($classificacao->admin)
			{{ Form::open(['route' => ['rodadas.store', $liga->id]]) }}
				<div class="modal fade" id="novaRodada">
		  			<div class="modal-dialog">
		    			<div class="modal-content">
		      				<div class="modal-header">
		        				<h5 class="modal-title">{{ __('content.nova-rodada') }}</h5>
		        				<button type="button" class="close" data-dismiss="modal">
		          					<span aria-hidden="true">&times;</span>
		        				</button>
		      				</div>
		      				<div class="modal-body">
		    					@include('rodadas._form')
		      				</div>
		      				<div class="modal-footer">
		      					<button type="submit" class="btn btn-success">{{ __('content.salvar') }}</button>
		        				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
		      				</div>
		    			</div>
		  			</div>
				</div>
			{{ Form::close() }}
		@endif

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.nome') }}</th>
						<th>{{ __('content.codigo') }}</th>
						<th>{{ __('content.data-inicial') }}</th>
						<th>{{ __('content.data-final') }}</th>
						<th>{{ __('content.rodadas-jogadas') }}</th>
						<th>{{ __('content.classificacao') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($liga->rodadas as $liga)
						<tr>
							<td>
								<a href="{{ route('ligas.show', $liga->id) }}">{{ $liga->liga->nome }}</a>

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
							<td>
								<a href="#" data-toggle="modal" data-target="#modalEditarLiga{{ $liga->liga->id }}"><i class="fas fa-pencil-alt text-success" title="{{ __('content.editar') }}"></i></a>
								@include('ligas.edit', ['liga' => $liga->liga, 'name' => 'modalEditarLiga'])
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
