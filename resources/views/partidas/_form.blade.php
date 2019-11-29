<div class="form-row">
	{!! Form::hidden('rodada_id', $rodada->id) !!}

	<div class="form-group col-sm-6">
		<label for="data">{{ __('content.data') }}</label>
		{!! Form::date('data', null, ['class' => 'form-control', 'id' => 'data', 'required']) !!}
	</div>
	<div class="form-group col-sm-6">
		<label for="hora">{{ __('content.hora') }}</label>
		{!! Form::time('hora', null, ['class' => 'form-control', 'id' => 'hora', 'required']) !!}
	</div>
</div>

<div class="form-row">
	<div class="form-group col-sm-9">
		<label for="mandante">{{ __('content.mandante') }}</label>
		{!! Form::text('mandante', null, ['class' => 'form-control', 'id' => 'mandante', 'maxlength' => 50, 'required']) !!}
	</div>
	<div class="form-group col-sm-3">
		<label for="golsmandante">{{ __('content.gols') }}</label>
		{!! Form::number('golsmandante', null, ['class' => 'form-control', 'id' => 'golsmandante', 'maxlength' => 2]) !!}
	</div>
</div>

<div class="form-row">
	<div class="form-group col-sm-9">
		<label for="visitante">{{ __('content.visitante') }}</label>
		{!! Form::text('visitante', null, ['class' => 'form-control', 'id' => 'visitante', 'maxlength' => 50, 'required']) !!}
	</div>
	<div class="form-group col-sm-3">
		<label for="golsvisitante">{{ __('content.gols') }}</label>
		{!! Form::number('golsvisitante', null, ['class' => 'form-control', 'id' => 'golsvisitante', 'maxlength' => 2]) !!}
	</div>
</div>

<div class="form-group">
	<label for="sigla">{{ __('content.sigla') }}</label>
	{!! Form::text('sigla', null, ['class' => 'form-control', 'id' => 'sigla', 'maxlength' => 7, 'required']) !!}
</div>
