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
		<label for="ativa">{{ __('content.ativa') }}</label>
		{{ Form::select('ativa', simnao(), null, ['class' => 'form-control', 'id' => 'ativa', 'required']) }}
	</div>
</div>

<div class="form-row">
	<div class="form-group col-md-3">
		<label for="pontos1">{{ __('content.pontos-da-resposta') }} 1</label>
		{{ Form::number('pontos1', null, ['class' => 'form-control', 'id' => 'pontos1']) }}
	</div>

	<div class="form-group col-md-3">
		<label for="pontos2">{{ __('content.pontos-da-resposta') }} 2</label>
		{{ Form::number('pontos2', null, ['class' => 'form-control', 'id' => 'pontos2']) }}
	</div>

	<div class="form-group col-md-3">
		<label for="pontos3">{{ __('content.pontos-da-resposta') }} 3</label>
		{{ Form::number('pontos3', null, ['class' => 'form-control', 'id' => 'pontos3']) }}
	</div>

	<div class="form-group col-md-3">
		<label for="pontos4">{{ __('content.pontos-da-resposta') }} 4</label>
		{{ Form::number('pontos4', null, ['class' => 'form-control', 'id' => 'pontos4']) }}
	</div>
</div>

@if ($respostas)
	<div class="form-row">
		<div class="form-group col-md-3">
			<label for="opcaocorreta1_id">{{ __('content.resposta') }} 1</label>
			{{ Form::select('opcaocorreta1_id', $pergunta->pluckOpcoes(), null, ['class' => 'form-control', 'id' => 'opcaocorreta1_id']) }}
		</div>

		<div class="form-group col-md-3">
			<label for="opcaocorreta2_id">{{ __('content.resposta') }} 2</label>
			{{ Form::select('opcaocorreta2_id', $pergunta->pluckOpcoes(), null, ['class' => 'form-control', 'id' => 'opcaocorreta2_id']) }}
		</div>

		<div class="form-group col-md-3">
			<label for="opcaocorreta3_id">{{ __('content.resposta') }} 3</label>
			{{ Form::select('opcaocorreta3_id', $pergunta->pluckOpcoes(), null, ['class' => 'form-control', 'id' => 'opcaocorreta3_id']) }}
		</div>

		<div class="form-group col-md-3">
			<label for="opcaocorreta4_id">{{ __('content.resposta') }} 4</label>
			{{ Form::select('opcaocorreta4_id', $pergunta->pluckOpcoes(), null, ['class' => 'form-control', 'id' => 'opcaocorreta4_id']) }}
		</div>
	</div>
@endif
