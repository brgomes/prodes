<div class="form-group">
	<label for="opcao">{{ __('content.opcao') }}</label>
	{{ Form::hidden('pergunta_id', $pergunta->id) }}
	{{ Form::text('opcao', null, ['class' => 'form-control', 'id' => 'opcao', 'maxlength' => 150, 'required']) }}
</div>
