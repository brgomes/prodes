@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-6">
			<div class="wrapper">
				<div class="wrapper-title">
					<div class="row">
						<div class="col-sm-6">
							<h2>{{ __('content.rodada') . ' #' . $rodada->numero }} - {{ $rodada->liga->nome }}</h2>
						</div>
						<div class="col-sm-6">
							@if ($classificacao->admin)
								<button class="btn btn-danger" data-toggle="modal" data-target="#consolidar">
									<i class="fas fa-check-circle"></i> {{ __('content.consolidar') }}
								</button>

								<button class="btn btn-success" data-toggle="modal" data-target="#novaPartida">
									<i class="fas fa-plus-circle"></i> {{ __('content.nova-partida') }}
								</button>
							@endif

							<a href="{{ route('ligas.show', $rodada->liga->id) }}" class="btn btn-secondary">
								<i class="fas fa-chevron-circle-left"></i> {{ __('content.voltar') }}
							</a>
						</div>
		            </div>
				</div>
				<div class="table-responsive">
					<div class="clearfix">
                		<div class="hint-text">{{ __('content.total-partidas') }}: <b>{{ $rodada->partidas->count() }}</b></div>
                	</div>
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

			@if ($classificacao->admin)
            	{{ Form::open(['route' => 'partidas.store']) }}
					<div class="modal fade" id="novaPartida">
			  			<div class="modal-dialog">
			    			<div class="modal-content">
			      				<div class="modal-header">
			        				<h5 class="modal-title">{{ __('content.nova-partida') }}</h5>
			        				<button type="button" class="close" data-dismiss="modal">
			          					<span aria-hidden="true">&times;</span>
			        				</button>
			      				</div>
			      				<div class="modal-body">
			    					@include('partidas._form')
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
		</div>
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
