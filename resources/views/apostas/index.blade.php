@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-6">
			<div class="wrapper">
				<div class="wrapper-title">
					<div class="row">
		                <h2>{{ __('content.apostas') }}</h2>
		            </div>
				</div>

				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>{{ __('content.liga') }}</th>
								<th>{{ __('content.mandante') }}</th>
								<th>{{ __('content.empate') }}</th>
								<th>{{ __('content.visitante') }}</th>
								<th>{{ __('content.data') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($partidas as $partida)
								<tr>
									<td>{{ $partida->rodada->liga->nome }}</td>
									<td>
										{!! Form::radio('palpite' . $partida->id, 'M', null, ['id' => 'palpiteM' . $partida->id]) !!}
										<label for="palpiteM{{ $partida->id }}">{{ $partida->mandante }}</label>
									</td>
									<td>
										{!! Form::radio('palpite' . $partida->id, 'E', null) !!}
									</td>
									<td>
										{!! Form::radio('palpite' . $partida->id, 'V', null, ['id' => 'palpiteV' . $partida->id]) !!}
										<label for="palpiteV{{ $partida->id }}">{{ $partida->visitante }}</label>
									</td>
									<td>{{ datetime($partida->datapartida, __('content.formato-datahora')) }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
			  	</div>
			</div>
		</div>
	</div>

@stop

@push('css')
	<link type="text/css" rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}"  media="screen,projection"/>
@endpush
