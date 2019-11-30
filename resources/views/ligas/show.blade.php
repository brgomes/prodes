@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="wrapper">
				<div class="wrapper-title">
					<h2>{{ $liga->nome }}</h2>
				</div>
				<div class="row">
					<div class="col-sm-2">
						{{ __('content.participantes') }}: <strong>{{ $liga->classificacao->count() }}</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.posicao') }}: <strong>{{ $classificacao->posicao }}9</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.pontos') }}: <strong>{{ $classificacao->pontos }}27</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.aproveitamento') }}: <strong>{{ $classificacao->aproveitamento }}48%</strong>
					</div>
					<div class="col-sm-2">
						<a href="#" data-toggle="modal" data-target="#regulamento">
							{{ __('content.classificacao') }}
						</a>
					</div>
					<div class="col-sm-2">
						@if ($liga->regulamento)
							<a href="#" data-toggle="modal" data-target="#regulamento">
								{{ __('content.regulamento') }}
							</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8 mt-4">
			<div class="wrapper">
				<div class="wrapper-title">
					<div class="row">
						<div class="col-sm-4">
							<h2>{{ __('content.rodadas') }}</h2>
						</div>
						<div class="col-sm-8">
							@if ($classificacao->admin)
								<button class="btn btn-success" data-toggle="modal" data-target="#consolidar">
									<i class="fas fa-check-circle"></i> {{ __('content.consolidar') }}
								</button>
							@endif

							@if ($classificacao->admin)
								<button class="btn btn-success" data-toggle="modal" data-target="#consolidar">
									<i class="fas fa-plus-circle"></i> {{ __('content.nova-partida') }}
								</button>
							@endif
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						Aqui o select das rodadas
					</div>
					<div class="col-sm-6 text-right">
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>{{ __('content.data') }}</th>
								<th>{{ __('content.partida') }}</th>
								<th>{{ __('content.sigla') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($rodada->partidas as $partida)
								<tr>
									<td>{{ datetime($partida->datapartida, __('content.formato-datahora')) }}</td>
									<td>
										<a href="#" data-toggle="modal" data-target="#modalEditarPartida{{ $partida->id }}">{!! $partida->descricao !!}</a>
										@include('partidas._edit', ['partida' => $partida])
										@include('partidas._delete', ['partida' => $partida])
									</td>
									<td>{{ $partida->sigla }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-sm-4 mt-4">
			<div class="wrapper">
				<div class="wrapper-title">
					<h2>{{ __('content.classificacao') }} da rodada</h2>
				</div>
			  	<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th></th>
								<th>{{ __('content.jogador') }}</th>
								<th>P</th>
								<th>RV</th>
								<th>%</th>
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
		</div>
	</div>


	@if ($classificacao->admin)
		<div class="modal fade" id="consolidar">
			<div class="modal-dialog">
				<div class="modal-content">
	  				<div class="modal-header">
	    				<h5 class="modal-title">{{ __('content.consolidar') }}</h5>
	    				<button type="button" class="close" data-dismiss="modal">
	      					<span aria-hidden="true">&times;</span>
	    				</button>
	  				</div>
	  				<div class="modal-body">
						Não sei se é preciso implementar o botão para consolidar toda a liga de uma vez.
	  				</div>
	  				<div class="modal-footer">
	    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
	  				</div>
				</div>
			</div>
		</div>
	@endif


	@if ($liga->regulamento)
		<div class="modal fade" id="regulamento">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
	  				<div class="modal-header">
	    				<h5 class="modal-title">{{ __('content.regulamento') }}</h5>
	    				<button type="button" class="close" data-dismiss="modal">
	      					<span aria-hidden="true">&times;</span>
	    				</button>
	  				</div>
	  				<div class="modal-body">
						{!! nl2br($liga->regulamento) !!}
	  				</div>
	  				<div class="modal-footer">
	    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
	  				</div>
				</div>
			</div>
		</div>
	@endif

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
