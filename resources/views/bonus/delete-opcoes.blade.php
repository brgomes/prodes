{!! Form::open(['route' => 'bonus.destroy-opcoes', 'method' => 'post']) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<h5 class="modal-title">{{ __('content.excluir-opcoes') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
			    @foreach ($pergunta->opcoes as $opcao)
                    <div class="custom-control custom-switch">
                        {{ Form::checkbox('opcoes[]', $opcao->id, false, ['id' => 'opcao' . $opcao->id, 'class' => 'custom-control-input']) }}
                        <label class="custom-control-label" for="opcao{{ $opcao->id }}">{{ $opcao->opcao }}</label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                {{ Form::hidden('pergunta_id', $pergunta->id) }}
            	<button type="submit" class="btn btn-danger">{{ __('content.excluir') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}
