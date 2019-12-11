@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="wrapper">
				<div class="wrapper-title bg-dark">
					<div class="row">
						<div class="col-sm-11">
							<h2>
								<a href="{{ route('ligas.show', $liga->id) }}">{{ $liga->nome }}</a>
								-
								{{ __('content.bonus-da-liga') }}
							</h2>
						</div>
						<div class="col-sm-1">
							@if ($jogador->admin)
								<div class="dropdown">
									<a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

									<div class="dropdown-menu" aria-labelledby="dropdown1">
										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('ligas.edit', $liga->id) }}">{{ __('content.nova-pergunta') }}</a>
										<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('ligas.delete', $liga->id) }}">{{ __('content.grupos-de-resposta') }}</a>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
	  	</div>
	</div>

@stop
