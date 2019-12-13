<div class="form-group">
	<label for="pergunta">{{ __('content.pergunta') }}</label>
	{{ Form::text('pergunta', null, ['class' => 'form-control', 'id' => 'pergunta', 'maxlength' => 200, 'required']) }}
</div>

<div class="form-row">
	<div class="form-group col-md-4">
		<label for="datalimiteresposta">{{ __('content.data-limite-resposta') }}</label>
		{{ Form::date('datalimiteresposta', null, ['class' => 'form-control', 'id' => 'datalimiteresposta', 'required']) }}
	</div>

	<div class="form-group col-md-4">
		<label for="qtderespostas">{{ __('content.qtde-respostas') }}</label>
		{{ Form::select('qtderespostas', [1 => 1,2 => 2,3 => 3,4 => 4], null, ['class' => 'form-control', 'id' => 'qtderespostas', 'required']) }}
	</div>

	<div class="form-group col-md-4">
		<label for="ativo">{{ __('content.ativa') }}</label>
		{{ Form::select('ativo', simnao(), null, ['class' => 'form-control', 'id' => 'ativo', 'required']) }}
	</div>
</div>
