{{ Form::model($partida, ['route' => ['partidas.destroy', $partida->id], 'method' => 'delete']) }}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            	<h5 class="modal-title">{{ __('content.excluir-partida') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<p>{{ __('message.confirma-exclusao-partida') }} {{ $partida->descricao }}?</p>
				<p class="text-danger"><small>{{ __('message.acao-nao-pode-ser-desfeita') }}</small></p>
            </div>
            <div class="modal-footer">
            	<button type="submit" class="btn btn-danger">{{ __('content.excluir') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
