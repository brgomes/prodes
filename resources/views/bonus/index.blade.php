@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-sm-12">
			<div class="wrapper mb-4">
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
										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNovaPergunta">{{ __('content.nova-pergunta') }}</a>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>

				@if ($perguntas->count() == 0)
					<div class="alert alert-info">{{ __('message.nenhuma-pergunta-bonus') }}</div>
				@else
					Colocar alguma coisa aqui
				@endif
			</div>
		</div>
	</div>

	@if ($perguntas->count() > 0)
		{!! Form::open(['route' => 'bonus.salvar-respostas']) !!}
			<div class="row">
				@foreach ($perguntas as $pergunta)
					<div class="col-sm-6">
						<div class="wrapper mb-4">
							<div class="wrapper-title bg-dark">
								<div class="row">
									<div class="col-sm-11">
										<h2>{{ $pergunta->pergunta }}</h2>
										{{ Form::hidden('perguntas[]', $pergunta->id) }}
									</div>
									<div class="col-sm-1">
										@if ($jogador->admin)
											<div class="dropdown dropleft">
												<a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>

												<div class="dropdown-menu" aria-labelledby="dropdown1">
													<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('bonus.edit-pergunta', $pergunta->id) }}">{{ __('content.editar-pergunta') }}</a>
													<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('bonus.create-opcao', $pergunta->id) }}">{{ __('content.adicionar-opcao') }}</a>
													<div class="dropdown-divider"></div>
													<a class="dropdown-item ajax-modal" href="#" data-url="{{ route('bonus.delete-opcoes', $pergunta->id) }}">{{ __('content.excluir-opcoes') }}</a>
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalNovaPergunta">{{ __('content.excluir-pergunta') }}</a>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							@if ($pergunta->qtderespostas == 1)
						    	<div class="form-group">
						    		{{ Form::select('resposta1_' . $pergunta->id, $pergunta->pluckOpcoes(), null, ['class' => 'form-control']) }}
						    	</div>
						    @endif
						</div>
					</div>
				@endforeach
	  		</div>

	  		{{ Form::hidden('liga_id', $liga->id )}}
	  		<button type="submit" class="btn btn-primary">{{ __('content.salvar-respostas') }}</button>
	  	{!! Form::close() !!}
	@endif

	<div class="modal fade" id="ajaxModal"></div>

	@if ($jogador->admin)
		{{ Form::open(['route' => ['bonus.store-pergunta', $liga->id], 'method' => 'post']) }}
			<div class="modal fade" id="modalNovaPergunta">
	  			<div class="modal-dialog modal-lg">
	    			<div class="modal-content">
	      				<div class="modal-header">
	        				<h5 class="modal-title">{{ __('content.nova-pergunta') }}</h5>
	        				<button type="button" class="close" data-dismiss="modal">
	          					<span aria-hidden="true">&times;</span>
	        				</button>
	      				</div>
	      				<div class="modal-body">
	    					@include('bonus._form-pergunta', ['respostas' => false])
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

@stop
