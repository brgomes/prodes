{{ Form::model($partida, ['route' => ['partidas.destroy', $partida->id], 'method' => 'delete']) }}
	<div class="modal fade" id="modalExcluirPartida{{ $partida->id }}">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	            	<h5 class="modal-title">{{ __('content.editar-liga') }}</h5>
	                <button type="button" class="close" data-dismiss="modal">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
					{{ __('message.confirma-exclusao-partida') }} {{ $partida->descricao }}?
	            </div>
	            <div class="modal-footer">
	            	<button type="submit" class="btn btn-danger">{{ __('content.excluir') }}</button>
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
	            </div>
	        </div>
	    </div>
	</div>
{{ Form::close() }}
