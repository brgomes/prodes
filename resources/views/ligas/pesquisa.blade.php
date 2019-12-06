@extends('layouts.default')

@section('content')

	<div class="wrapper">
		<div class="wrapper-title bg-dark">
			<div class="row">
				<h2>{{ __('content.pesquisa-de-liga') }}</h2>
            </div>
		</div>

		@if ($liga)
			<div class="table-responsive mb-3">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>{{ __('content.nome') }}</th>
							<th>{{ __('content.codigo') }}</th>
							<th>{{ __('content.data-inicial') }}</th>
							<th>{{ __('content.data-final') }}</th>
							<th>{{ __('content.participantes') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{{ $liga->nome }}</td>
							<td>{{ $liga->codigo }}</td>
							<td>{{ datetime($liga->datainicio, __('content.formato-data')) }}</td>
							<td>{{ datetime($liga->datafim, __('content.formato-data')) }}</td>
							<td>{{ $liga->jogadores()->count() }}</td>
							<td>
								@if ($liga->regulamento)
									<button class="btn btn-primary" data-toggle="modal" data-target="#regulamento"><i class="far fa-file-alt"></i> {{ __('content.regulamento') }}</button>
								@endif

								@if ($jogador)
									<button class="btn btn-secondary" disabled><i class="far fa-times-circle"></i> {{ __('content.ja-participa') }}</button>
								@else
									<button class="btn btn-success" data-toggle="modal" data-target="#participar"><i class="fas fa-sign-in-alt"></i> {{ __('content.participar-da-liga') }}</button>
								@endif
							</td>
						</tr>
					</tbody>
				</table>
		  	</div>
		@else
			<div class="alert alert-warning mb-0">
				{{ __('message.liga-nao-encontrada') }}
			</div>
		@endif
	</div>


	@if ($liga)
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

		@if (!$jogador)
			{{ Form::open(['route' => ['ligas.entrar', $liga->id]]) }}
				<div class="modal fade" id="participar">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
			  				<div class="modal-header">
			    				<h5 class="modal-title">{{ __('content.participar-da-liga') }}</h5>
			    				<button type="button" class="close" data-dismiss="modal">
			      					<span aria-hidden="true">&times;</span>
			    				</button>
			  				</div>
			  				<div class="modal-body">
								{{ __('message.entrar-na-liga') . ' ' . $liga->nome }}?
			  				</div>
			  				<div class="modal-footer">
			  					<button type="submit" class="btn btn-success">{{ __('content.sim') }}</button>
			    				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.nao') }}</button>
			  				</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		@endif
	@endif

@stop
