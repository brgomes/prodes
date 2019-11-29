@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="wrapper">
				<div class="wrapper-title">
					<div class="row">
						<div class="col-sm-6">
							<h2>{{ $liga->nome }}</h2>
						</div>
						<div class="col-sm-6">
							@if ($classificacao->admin)
								<a href="{{ route('ligas.consolidar', $liga->id) }}" class="btn btn-success">
									<i class="fas fa-check-circle"></i> {{ __('content.consolidar') }}
								</a>
							@endif
						</div>
		            </div>
				</div>
				<div>
					<div class="row">
						<div class="col-sm-4">
							{{ __('content.data-inicial') }}: <strong>{{ datetime($liga->datainicio, __('content.formato-data')) }}</strong>
						</div>
						<div class="col-sm-4">
							{{ __('content.data-final') }}: <strong>{{ datetime($liga->datafim, __('content.formato-data')) }}</strong>
						</div>
						<div class="col-sm-4">
							{{ __('content.data-consolidacao') }}: <strong>{{ datetime($liga->dataconsolidacao, __('content.formato-data')) }}</strong>
						</div>
					</div>

					@if ($liga->regulamento)
						<div class="row mt-3">
							<div class="col-sm-12">
								{!! nl2br($liga->regulamento) !!}
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>


	<div class="wrapper mt-5">
		<div class="wrapper-title">
			<div class="row">
				<h2>{{ __('content.classificacao') }}</h2>
            </div>
		</div>
	  	<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ __('content.posicao') }}</th>
						<th>{{ __('content.jogador') }}</th>
						<th>{{ __('content.pontos') }}</th>
						<th>{{ __('content.rodadas-vencidas') }}</th>
						<th>{{ __('content.aproveitamento') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($liga->classificacao as $item)
						<tr>
							<td>@if ($item->posicao) {{ $item->posicao }} @else - @endif</td>
							<td>
								<span class="{{ $item->usuario->bandeira }}" title="{{ $item->usuario->pais->nome }}"></span>
								{{ $item->usuario->primeironome . ' ' . $item->usuario->sobrenome }}
							</td>
							<td>{{ $item->pontos }}</td>
							<td>{{ $item->rodadasjogadas }}</td>
							<td>@if ($item->aproveitamento) {{ $item->aproveitamento }} @else - @endif</td>
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
		      					{!! Form::hidden('liga_id', $liga->id) !!}

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
						<th>{{ __('content.numero') }}</th>
						<th>{{ __('content.data-consolidacao') }}</th>
						<th>{{ __('content.partidas') }}</th>
						<th>{{ __('content.vencedor') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($liga->rodadas as $rodada)
						<tr>
							<td><a href="{{ route('rodadas.show', $rodada->id) }}">#{{ $rodada->numero }}</a></td>
							<td>{{ datetime($rodada->dataconsolidacao, __('content.formato-datahora-completo')) }}</td>
							<td>{{ $rodada->partidas->count() }}</td>
							<td></td>
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
