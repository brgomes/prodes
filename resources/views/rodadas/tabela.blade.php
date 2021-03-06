@extends('layouts.default')

@section('content')

	@if ($admin)
		<div class="alert alert-info mb-4">
			{{ __('message.admin-ve-palpites') }}
		</div>
	@endif

	<div class="wrapper">
		<div class="wrapper-title bg-dark">
			<div class="row text-center">
				<h2>
					<a href="{{ route('ligas.show', $rodada->liga_id) }}">{{ $rodada->liga->nome }}</a>
					-
					<a href="{{ route('ligas.show', [$rodada->liga_id, $rodada->id]) }}">{{ __('content.rodada') }} {{ $rodada->numero }}</a>
				</h2>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr class="bg-dark text-light">
						<th>{{ __('content.jogador') }}</th>
						
						@foreach ($rodada->partidas as $partida)
							<th class="text-center">{{ $partida->sigla }}</th>
						@endforeach

						<th class="text-center">{{ __('content.total') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($jogadores as $jogador)
						<tr>
							<td>{{ $jogador->usuario->primeironome . ' ' . $jogador->usuario->sobrenome }}</td>

							@foreach ($rodada->partidas as $partida)
								@if (!$admin && $partida->aberta())
									<td></td>
								@elseif (!$partida->temresultado && !$admin)
									<td></td>
								@else
									@if ($palpite = $jogador->palpite($partida->id))
										@if ($palpite->consolidado)
											@if ($palpite->nivelacerto == 'T')
												<td class="text-center bg-success text-light">
													@if ($rodada->liga->tipo == 'P')
														<strong>{{ $palpite->palpitegolsm . '-' . $palpite->palpitegolsv }}</strong>
													@elseif ($rodada->liga->tipo == 'V')
														<strong>{{ __('content.sigla-palpite' . $palpite->palpite) }}</strong>
													@endif

													@if ($palpite->coringa)
														*
													@endif
												</td>
											@elseif ($palpite->nivelacerto == 'P')
												<td class="text-center bg-warning text-light">
													@if ($rodada->liga->tipo == 'P')
														<strong>{{ $palpite->palpitegolsm . '-' . $palpite->palpitegolsv }}</strong>
													@elseif ($rodada->liga->tipo == 'V')
														<strong>{{ __('content.sigla-palpite' . $palpite->palpite) }}</strong>
													@endif

													@if ($palpite->coringa)
														*
													@endif
												</td>
											@else
												<td class="text-center bg-danger text-light">
													@if ($rodada->liga->tipo == 'P')
														<strong>{{ $palpite->palpitegolsm . '-' . $palpite->palpitegolsv }}</strong>
													@elseif ($rodada->liga->tipo == 'V')
														<strong>{{ __('content.sigla-palpite' . $palpite->palpite) }}</strong>
													@endif

													@if ($palpite->coringa)
														*
													@endif
												</td>
											@endif
										@elseif ($admin)
											<td class="text-center">
												@if ($rodada->liga->tipo == 'P')
													{{ $palpite->palpitegolsm . '-' . $palpite->palpitegolsv }}
												@elseif ($rodada->liga->tipo == 'V')
													{{ __('content.sigla-palpite' . $palpite->palpite) }}
												@endif

												@if ($palpite->coringa)
													*
												@endif
											</td>
										@else
											<td></td>
										@endif
									@else
										<td></td>
									@endif
								@endif
							@endforeach

							<td class="text-center">
								<strong>{{ $jogador->pontosNaRodada($rodada->id) }}</strong>
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
