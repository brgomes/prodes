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
										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalRegulamento">{{ __('content.regulamento') }}</a>

										@if ($jogador->admin)
											<div class="dropdown-divider"></div>
										@endif
									@endif

									@if ($jogador->admin)
										@if (isset($rodada))
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#consolidarLiga">{{ __('content.consolidar') }}</a>
											<div class="dropdown-divider"></div>
										@endif

										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('ligas.edit', $liga->id) }}">{{ __('content.editar-liga') }}</a>
										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('ligas.delete', $liga->id) }}">{{ __('content.excluir-liga') }}</a>
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
										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('rodadas.classificacao', $rodada->id) }}">{{ __('content.classificacao-da-rodada') }}</a>
									@endif

									@if ($jogador->admin)
										@if (isset($rodada))
											<div class="dropdown-divider"></div>
										@endif

										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('rodadas.create', $liga->id) }}">{{ __('content.nova-rodada') }}</a>

										@if (isset($rodada))
											<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('rodadas.edit', $rodada->id) }}">{{ __('content.editar-rodada') }}</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('partidas.create', $rodada->id) }}">{{ __('content.nova-partida') }}</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('rodadas.delete', $rodada->id) }}">{{ __('content.excluir-rodada') }}</a>
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

										@if ($liga->tipo == 'P')
											<th class="text-right">{{ __('content.mandante') }}</th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th>{{ __('content.visitante') }}</th>
										@elseif ($liga->tipo == 'V')
											<th class="text-right">{{ __('content.mandante') }}</th>
											<th class="text-center">{{ __('content.empate') }}</th>
											<th>{{ __('content.visitante') }}</th>
										@endif

										@if ($liga->temcoringa)
											<th class="text-center">{{ __('content.coringa') }}</th>
										@endif

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

											@if ($liga->tipo == 'P')
												<td class="text-right">
													{{ $partida->mandante }}
												</td>
												<td class="text-center">
													@if ($partida->aberta())
														@if ($partida->palpite)
															{{ Form::number('palpitem-' . $partida->id, $partida->palpite->palpitegolsm, ['class' => 'form-control', 'style' => 'width:70px']) }}
														@else
															{{ Form::number('palpitem-' . $partida->id, null, ['class' => 'form-control', 'style' => 'width:70px']) }}
														@endif
													@else
														@if ($partida->palpite)
															{{ $partida->palpite->palpitegolsm }}
														@else
															-
														@endif
													@endif
												</td>
												<td class="text-center">
													@if ($partida->aberta())
														@if ($partida->palpite)
															{{ Form::number('palpitev-' . $partida->id, $partida->palpite->palpitegolsv, ['class' => 'form-control', 'style' => 'width:70px']) }}
														@else
															{{ Form::number('palpitev-' . $partida->id, null, ['class' => 'form-control', 'style' => 'width:70px']) }}
														@endif
													@else
														@if ($partida->palpite)
															{{ $partida->palpite->palpitegolsv }}
														@else
															-
														@endif
													@endif
												</td>
												<td>
													{{ $partida->visitante }}
												</td>
											@elseif ($liga->tipo == 'V')
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
											@endif

											@if ($liga->temcoringa)
												<td class="text-center">
													@if ($partida->aberta())
														@if ($habilita_coringa)
															@if ($partida->palpite)
																{{ Form::radio('coringa', $partida->id, $partida->palpite->coringa, ['class' => 'palpite']) }}
															@else
																{{ Form::radio('coringa', $partida->id, null, ['class' => 'palpite']) }}
															@endif
														@endif
													@else
														@if ($partida->palpite)
															@if ($partida->palpite->coringa)
																<i class="fas fa-check-circle"></i>
															@endif
														@else
														@endif
													@endif
												</td>
											@endif

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
															<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('partidas.edit', $partida->id) }}">{{ __('content.editar-partida') }}</a>
															<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('partidas.delete', $partida->id) }}">{{ __('content.excluir-partida') }}</a>
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


		<div class="col-sm-5 mt-4">
			<div class="wrapper">
				<div class="wrapper-title bg-dark">
					<h2>{{ __('content.classificacao-da-liga') }}</h2>
				</div>
			  	<div class="table-responsive">
			  		<table class="table table-striped">
						<thead>
							<tr>
								<th></th>
								<th>{{ __('content.jogador') }}</th>
								<th class="text-center">{{ __('content.sigla-pontos') }}</th>
								<th class="text-center">{{ __('content.sigla-rodadas-vencidas') }}</th>
								<th class="text-center">%</th>

								@if ($jogador->admin)
									<th class="text-center"></th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach ($classificacao as $item)
								@if ($item->id == $jogador->id)
									<tr class="table-success">
								@else
									<tr>
								@endif

									<td>@if ($item->posicao) {{ $item->posicao }}º @else - @endif</td>
									<td>
										<span class="{{ $item->usuario->bandeira }}" title="{{ $item->usuario->pais->nome }}"></span>
										{{ $item->usuario->primeironome . ' ' . $item->usuario->sobrenome }}

										@if ($item->admin)
											<span class="fas fa-star text-warning" title="{{ __('content.administrador') }}"></span>
										@endif
									</td>
									<td class="text-center">{{ $item->pontosganhos }}</td>
									<td class="text-center">{{ $item->rodadasvencidas }}</td>
									<td class="text-center">@if ($item->aproveitamento) {{ $item->aproveitamentof . '%' }} @else - @endif</td>

									@if ($jogador->admin)
										<td class="text-center">
											<div class="dropdown">
												<a class="btn dropdown-toggle dropdown-sm" href="#" role="button" id="dd_class{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

												<div class="dropdown-menu" aria-labelledby="dd_class{{ $item->id }}">
													@if ($item->admin)
														<a class="dropdown-item" href="{{ route('ligas.remover-admin', [$liga->id, $item->usuario_id]) }}">{{ __('content.remover-admin') }}</a>
													@else
														<a class="dropdown-item" href="{{ route('ligas.setar-admin', [$liga->id, $item->usuario_id]) }}">{{ __('content.setar-como-admin') }}</a>
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
		</div>
	</div>


	<div class="modal fade" id="ajaxModal"></div>


	@if ($jogador->admin)
		@if (isset($rodada))
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
		@endif
	@endif


	@if ($liga->regulamento)
		<div class="modal fade" id="modalRegulamento">
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
