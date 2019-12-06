@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="wrapper">
				<div class="wrapper-title bg-dark">
					<div class="row">
						<div class="col-sm-11">
							<h2><a href="{{ route('ligas.show', $liga->id) }}">{{ $liga->nome }}</a></h2>
							{!! Form::hidden('liga_id', $liga->id, ['id' => 'liga_id']) !!}
						</div>
						<div class="col-sm-1">
							<div class="dropdown">
								<a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

								<div class="dropdown-menu" aria-labelledby="dropdown1">
									@if ($liga->regulamento)
										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#regulamento">{{ __('content.regulamento') }}</a>
									@endif

									<a class="dropdown-item" href="#" data-toggle="modal" data-target="#classificacao">{{ __('content.classificacao') }}</a>

									@if ($jogador->admin)
										@if (isset($rodada))
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#consolidarLiga">{{ __('content.consolidar') }}</a>
											<div class="dropdown-divider"></div>
										@endif
										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalExcluirLiga">{{ __('content.excluir-liga') }}</a>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					@if ($jogador->admin)
						<div class="col-sm-2">
							@if ($liga->consolidar)
								<i class="fas fa-exclamation-triangle text-warning"></i> {{ __('content.precisa-consolidar') }}
							@else
								<i class="fas fa-check-circle text-success"></i> {{ __('content.consolidada') }}
							@endif
						</div>
					@endif

					<div class="col-sm-2">
						{{ __('content.participantes') }}: <strong>{{ $liga->jogadores->count() }}</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.posicao') }}: <strong>{{ $jogador->posicao }}</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.pontos') }}: <strong>{{ $jogador->pontosganhos }}</strong>
					</div>
					<div class="col-sm-2">
						{{ __('content.aproveitamento') }}: <strong>{{ $jogador->aproveitamentof }}%</strong>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-7 mt-4">
			<div class="wrapper">
				<div class="wrapper-title bg-dark">
					<div class="row">
						<div class="col-sm-11">
							<h2>{{ __('content.rodadas') }}</h2>
						</div>
						<div class="col-sm-1">
							<div class="dropdown">
								<a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

								<div class="dropdown-menu" aria-labelledby="dropdown2">
									@if (isset($rodada))
										<a class="dropdown-item" href="{{ route('rodadas.tabela-resultado', $rodada->id) }}">{{ __('content.tabela-resultado') }}</a>
									@endif

									@if ($jogador->admin)
										@if (isset($rodada))
											<div class="dropdown-divider"></div>
										@endif

										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#novaRodada">{{ __('content.nova-rodada') }}</a>

										@if (isset($rodada))
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarRodada">{{ __('content.editar-rodada') }}</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#novaPartida">{{ __('content.nova-partida') }}</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalExcluirRodada">{{ __('content.excluir-rodada') }}</a>
										@endif
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						@if (isset($rodada))
							{{ Form::select('rodada_id', rodadas($liga->id), $rodada->id, ['id' => 'select-rodada']) }}
						@endif
					</div>
				</div>

				@if (isset($rodada))
					{{ Form::open(['route' => ['palpitar', $rodada->id]]) }}
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>{{ __('content.data') }}</th>
										<th class="text-right">{{ __('content.mandante') }}</th>
										<th class="text-center">{{ __('content.empate') }}</th>
										<th>{{ __('content.visitante') }}</th>
										<th class="text-center">{{ __('content.resultado') }}</th>

										@if ($jogador->admin)
											<th>{{ __('content.sigla') }}</th>
											<th></th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ($rodada->partidas as $i => $partida)
										@if ($partida->temresultado)
											@if ($partida->cancelada)
												<tr class="table-active">
											@else
												@if ($partida->palpite)
													@if ($partida->palpite->consolidado)
														@if ($partida->palpite->palpite == $partida->vencedor)
															<tr class="table-success">
														@else
															<tr class="table-danger">
														@endif
													@else
														<tr class="table-warning">
													@endif
												@else
													<tr>
												@endif
											@endif
										@else
											<tr>
										@endif

											<td>{{ $i+1 }}</td>
											<td>
												{{ datetime($partida->datapartida, __('content.formato-datahora')) }}
												{!! Form::hidden('partidas[]', $partida->id) !!}
											</td>
											<td class="text-right">
												<small class="text-secondary">{{ $partida->percentualMandante() }}%</small>
												@if ($partida->aberta())
													<label for="palpiteM{{ $partida->id }}">{{ $partida->mandante }}</label>

													@if ($partida->palpite)
														{!! Form::radio('palpite-' . $partida->id, 'M', ($partida->palpite->palpite == 'M'), ['id' => 'palpiteM' . $partida->id, 'class' => 'palpite']) !!}
													@else
														{!! Form::radio('palpite-' . $partida->id, 'M', null, ['id' => 'palpiteM' . $partida->id, 'class' => 'palpite']) !!}
													@endif
												@else
													@if ($partida->palpite)
														@if ($partida->palpite->palpite == 'M')
															<strong>{{ $partida->mandante }}</strong>
															<i class="fas fa-check-circle"></i>
														@else
															{{ $partida->mandante }}
														@endif
													@else
														{{ $partida->mandante }}
													@endif
												@endif
											</td>
											<td class="text-center">
												@if ($partida->aberta())
													@if ($partida->palpite)
														{!! Form::radio('palpite-' . $partida->id, 'E', ($partida->palpite->palpite == 'E'), ['class' => 'palpite']) !!}
													@else
														{!! Form::radio('palpite-' . $partida->id, 'E', null, ['class' => 'palpite']) !!}
													@endif
												@else
													@if ($partida->palpite)
														@if ($partida->palpite->palpite == 'E')
															<i class="fas fa-check-circle"></i>
														@endif
													@endif
												@endif
											</td>
											<td>
												@if ($partida->aberta())
													@if ($partida->palpite)
														{!! Form::radio('palpite-' . $partida->id, 'V', ($partida->palpite->palpite == 'V'), ['id' => 'palpiteV' . $partida->id, 'class' => 'palpite']) !!}
													@else
														{!! Form::radio('palpite-' . $partida->id, 'V', null, ['id' => 'palpiteV' . $partida->id, 'class' => 'palpite']) !!}
													@endif

													<label for="palpiteV{{ $partida->id }}">{{ $partida->visitante }}</label>
												@else
													@if ($partida->palpite)
														@if ($partida->palpite->palpite == 'V')
															<strong>{{ $partida->visitante }}</strong>
															<i class="fas fa-check-circle"></i>
														@else
															{{ $partida->visitante }}
														@endif
													@else
														{{ $partida->visitante }}
													@endif
												@endif
												<small class="text-secondary">{{ $partida->percentualVisitante() }}%</small>
											</td>
											<td class="text-center">
												@if ($partida->temresultado)
													<span class="text-secondary">
														{!! $partida->resultado !!}
													</span>
												@endif
											</td>

											@if ($jogador->admin)
												<td>{{ $partida->sigla }}</td>
												<td>
													<div class="dropdown">
														<a class="btn dropdown-toggle dropdown-sm" href="#" role="button" id="dd_partida{{ $partida->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

														<div class="dropdown-menu" aria-labelledby="dd_partida{{ $partida->id }}">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalEditarPartida{{ $partida->id }}">{{ __('content.editar-partida') }}</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalExcluirPartida{{ $partida->id }}">{{ __('content.excluir-partida') }}</a>
														</div>
													</div>
												</td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<button type="submit" class="btn btn-secondary mt-3">Salvar apostas</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>


		@if (isset($rodada))
			@if ($jogador->admin)
				@foreach ($rodada->partidas as $partida)
					@include('partidas._edit', ['partida' => $partida])
					@include('partidas._delete', ['partida' => $partida])
				@endforeach
			@endif
		@endif


		<div class="col-sm-5 mt-4">
			@if (isset($rodada))
				<div class="wrapper">
					<div class="wrapper-title bg-dark">
						<h2>{{ __('content.classificacao-da-rodada') }}</h2>
					</div>
				  	<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th></th>
									<th>{{ __('content.jogador') }}</th>
									<th>{{ __('content.pontos') }}</th>
									<th>%</th>

									@if ($jogador->admin)
										<th></th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach ($rodada->classificacao as $item)
									@if ($item->jogador_id == $jogador->id)
										<tr class="table-success">
									@else
										<tr>
									@endif

										<td>@if ($item->posicao) {{ $item->posicao }} @else - @endif</td>
										<td>
											<span class="{{ $item->jogador->usuario->bandeira }}" title="{{ $item->jogador->usuario->pais->nome }}"></span>
											{{ $item->jogador->usuario->primeironome . ' ' . $item->jogador->usuario->sobrenome }}

											@if ($item->jogador->admin)
												<span class="fas fa-star text-warning" title="Admin"></span>
											@endif
										</td>
										<td>{{ $item->pontosganhos }}</td>
										<td>@if ($item->aproveitamento) {{ $item->aproveitamentof . '%' }} @else - @endif</td>

										@if ($jogador->admin)
											<td>
												<div class="dropdown">
													<a class="btn dropdown-toggle dropdown-sm" href="#" role="button" id="dd_class{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

													<div class="dropdown-menu" aria-labelledby="dd_class{{ $item->id }}">
														@if ($item->jogador->admin)
															<a class="dropdown-item" href="{{ route('ligas.remover-admin', [$liga->id, $item->jogador->usuario_id]) }}">{{ __('content.remover-admin') }}</a>
														@else
															<a class="dropdown-item" href="{{ route('ligas.setar-admin', [$liga->id, $item->jogador->usuario_id]) }}">{{ __('content.setar-como-admin') }}</a>
														@endif
													</div>
												</div>
											</td>
										@endif
									</tr>
								@endforeach
							</tbody>
						</table>
				  	</div>
				</div>
			@endif
		</div>
	</div>


	@if ($jogador->admin)
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
		    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
		  				</div>
					</div>
				</div>
			</div>
		{{ Form::close() }}


		@if (isset($rodada))
			{{ Form::model($rodada, ['route' => ['rodadas.update', $rodada->id], 'method' => 'put']) }}
				<div class="modal fade" id="editarRodada">
					<div class="modal-dialog">
						<div class="modal-content">
			  				<div class="modal-header">
			    				<h5 class="modal-title">{{ __('content.editar-rodada') }}</h5>
			    				<button type="button" class="close" data-dismiss="modal">
			      					<span aria-hidden="true">&times;</span>
			    				</button>
			  				</div>
			  				<div class="modal-body">
								@include('rodadas._form')
			  				</div>
			  				<div class="modal-footer">
			  					<button type="submit" class="btn btn-success">{{ __('content.salvar') }}</button>
			    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
			  				</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}


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


			{{ Form::open(['route' => ['ligas.consolidar', $rodada->liga_id, $rodada->id]]) }}
				<div class="modal fade" id="consolidarLiga">
					<div class="modal-dialog">
						<div class="modal-content">
			  				<div class="modal-header">
			    				<h5 class="modal-title">{{ __('content.consolidar') }}</h5>
			    				<button type="button" class="close" data-dismiss="modal">
			      					<span aria-hidden="true">&times;</span>
			    				</button>
			  				</div>
			  				<div class="modal-body">
								{{ __('message.confirma-consolidacao') }}
			  				</div>
			  				<div class="modal-footer">
			  					<button type="submit" class="btn btn-success">{{ __('content.consolidar') }}</button>
			    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
			  				</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}


			{{ Form::open(['route' => ['rodadas.destroy', $rodada->id], 'method' => 'delete']) }}
				<div class="modal fade" id="modalExcluirRodada">
					<div class="modal-dialog">
						<div class="modal-content">
			  				<div class="modal-header">
			    				<h5 class="modal-title">{{ __('content.excluir-rodada') }}</h5>
			    				<button type="button" class="close" data-dismiss="modal">
			      					<span aria-hidden="true">&times;</span>
			    				</button>
			  				</div>
			  				<div class="modal-body">
								<p>{{ __('message.confirma-exclusao-rodada') }} {{ $rodada->numero }}?</p>
								<p class="text-danger"><small>{{ __('message.acao-nao-pode-ser-desfeita') }}</small></p>
			  				</div>
			  				<div class="modal-footer">
			  					<button type="submit" class="btn btn-danger">{{ __('content.excluir') }}</button>
			    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
			  				</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		@endif


		{{ Form::open(['route' => ['ligas.destroy', $liga->id], 'method' => 'delete']) }}
			<div class="modal fade" id="modalExcluirLiga">
				<div class="modal-dialog">
					<div class="modal-content">
		  				<div class="modal-header">
		    				<h5 class="modal-title">{{ __('content.excluir-liga') }}</h5>
		    				<button type="button" class="close" data-dismiss="modal">
		      					<span aria-hidden="true">&times;</span>
		    				</button>
		  				</div>
		  				<div class="modal-body">
							<p>{{ __('message.confirma-exclusao-liga') }} <strong>{{ $liga->nome }}</strong>?</p>
							<p class="text-danger"><small>{{ __('message.acao-nao-pode-ser-desfeita') }}</small></p>
		  				</div>
		  				<div class="modal-footer">
		  					<button type="submit" class="btn btn-danger">{{ __('content.excluir') }}</button>
		    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
		  				</div>
					</div>
				</div>
			</div>
		{{ Form::close() }}
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

	<div class="modal fade" id="classificacao">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
  				<div class="modal-header">
    				<h5 class="modal-title">{{ __('content.classificacao') }}</h5>
    				<button type="button" class="close" data-dismiss="modal">
      					<span aria-hidden="true">&times;</span>
    				</button>
  				</div>
  				<div class="modal-body">
  					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th></th>
									<th>{{ __('content.jogador') }}</th>
									<th>{{ __('content.pontos') }}</th>
									<th>{{ __('content.rodadas-vencidas') }}</th>
									<th>%</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($classificacao as $item)
									@if ($item->id == $jogador->id)
										<tr class="table-success">
									@else
										<tr>
									@endif

										<td>@if ($item->posicao) {{ $item->posicao }} @else - @endif</td>
										<td>
											<span class="{{ $item->usuario->bandeira }}" title="{{ $item->usuario->pais->nome }}"></span>
											{{ $item->usuario->primeironome . ' ' . $item->usuario->sobrenome }}
										</td>
										<td>{{ $item->pontosganhos }}</td>
										<td>{{ $item->rodadasvencidas }}</td>
										<td>@if ($item->aproveitamento) {{ $item->aproveitamentof . '%' }} @else - @endif</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
  				</div>
  				<div class="modal-footer">
    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
  				</div>
			</div>
		</div>
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
