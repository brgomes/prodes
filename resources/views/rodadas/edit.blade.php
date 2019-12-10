{{ Form::model($rodada, ['route' => ['rodadas.update', $rodada->id], 'method' => 'put']) }}
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
