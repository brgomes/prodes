{{ Form::model($partida, ['route' => ['partidas.update', $partida->id], 'method' => 'put']) }}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<h5 class="modal-title">{{ __('content.editar-partida') }}</h5>
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
{{ Form::close() }}
