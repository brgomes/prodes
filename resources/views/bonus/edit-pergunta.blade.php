{{ Form::model($pergunta, ['route' => 'bonus.update-pergunta', 'method' => 'put']) }}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('content.editar-pergunta') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('bonus._form-pergunta', ['respostas' => true])
            </div>
            <div class="modal-footer">
                {{ Form::hidden('pergunta_id', $pergunta->id) }}
                <button type="submit" class="btn btn-success">{{ __('content.salvar') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
            </div>
        </div>
    </div>
{{ Form::close() }}
