<div class="form-row">
	<div class="form-group col-md-6">
		<label for="nome">{{ __('content.nome') }}</label>
		{{ Form::text('nome', null, ['class' => 'form-control', 'id' => 'nome', 'maxlength' => 50, 'required']) }}
	</div>

	<div class="form-group col-md-6">
		<label for="tipo">{{ __('content.tipo-de-liga') }}</label>
		{{ Form::select('tipo', tiposLiga(), null, ['class' => 'form-control', 'id' => 'tipo', 'required']) }}
	</div>
</div>

<div class="form-row">
	<div class="form-group col-md-6">
		<label for="datainicio">{{ __('content.data-inicial') }}</label>
		{{ Form::date('datainicio', null, ['class' => 'form-control', 'id' => 'datainicio', 'required']) }}
	</div>

	<div class="form-group col-md-6">
		<label for="datafim">{{ __('content.data-final') }}</label>
		{{ Form::date('datafim', null, ['class' => 'form-control', 'id' => 'datafim', 'required']) }}
	</div>
</div>

<div class="row">
	<div class="form-group col-md-4">
		<label for="datalimiteentrada">{{ __('content.data-limite-entrada-liga') }}</label>
		{{ Form::date('datalimiteentrada', null, ['class' => 'form-control', 'id' => 'datalimiteentrada']) }}
	</div>

	<div class="form-group col-md-4">
		<label for="pontosacertoplacar">{{ __('content.pontos-acerto-placar') }}</label>
		{{ Form::number('pontosacertoplacar', null, ['class' => 'form-control', 'id' => 'pontosacertoplacar', 'required']) }}
	</div>

	<div class="form-group col-md-4">
		<label for="pontosacertovencedor">{{ __('content.pontos-acerto-vencedor') }}</label>
		{{ Form::number('pontosacertovencedor', null, ['class' => 'form-control', 'id' => 'pontosacertovencedor', 'required']) }}
	</div>
</div>

<div class="form-group">
	<label for="regulamento">{{ __('content.regulamento') }}</label>
	{{ Form::textarea('regulamento', null, ['class' => 'form-control', 'id' => 'regulamento']) }}
</div>

<div class="form-group form-check">
	{!! Form::checkbox('temcoringa', '1', null, ['class' => 'form-check-input', 'id' => 'temcoringa']) !!}
	<label class="form-check-label" for="temcoringa">{{ __('content.habilitar-coringa') }}</label>
	<small class="form-text text-muted">
  		{{ __('message.explicacao-coringa') }}
	</small>
</div>
