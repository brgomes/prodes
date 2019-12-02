<div class="form-group">
	<label for="numero">{{ __('content.numero') }}</label>
	{!! Form::number('numero', null, ['class' => 'form-control', 'id' => 'numero', 'required']) !!}
</div>

<div class="form-row">
	<div class="form-group col-sm-8">
		<label for="datainicial">{{ __('content.data-inicial') }}</label>
		{!! Form::date('datainicial', null, ['class' => 'form-control', 'id' => 'datainicial', 'required']) !!}
	</div>
	<div class="form-group col-sm-4">
		<label for="horainicial">{{ __('content.hora-inicial') }}</label>
		{!! Form::text('horainicial', null, ['class' => 'form-control time', 'id' => 'horainicial', 'required']) !!}
		<small class="form-text text-muted">
		  	{{ __('content.desc-formato-hora') }}
		</small>
	</div>
</div>

<div class="form-row">
	<div class="form-group col-sm-8">
		<label for="datafinal">{{ __('content.data-final') }}</label>
		{!! Form::date('datafinal', null, ['class' => 'form-control', 'id' => 'datafinal', 'required']) !!}
	</div>
	<div class="form-group col-sm-4">
		<label for="horafinal">{{ __('content.hora-final') }}</label>
		{!! Form::text('horafinal', null, ['class' => 'form-control time', 'id' => 'horafinal', 'required']) !!}
		<small class="form-text text-muted">
		  	{{ __('content.desc-formato-hora') }}
		</small>
	</div>
</div>
