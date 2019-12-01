<div class="form-group row">
	<label class="col-sm-4 col-form-label" for="numero">{{ __('content.numero') }}</label>
	<div class="col-sm-8">
		{!! Form::number('numero', null, ['class' => 'form-control', 'id' => 'numero', 'required']) !!}
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-4 col-form-label" for="datainicio">{{ __('content.data-hora-inicial') }}</label>
	<div class="col-sm-8">
		{!! Form::text('datainicio', null, ['class' => 'form-control datetime', 'id' => 'datainicio', 'required']) !!}
		<small class="form-text text-muted">
		  	{{ __('content.desc-formato-datahora') }}
		</small>
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-4 col-form-label" for="datafim">{{ __('content.data-hora-final') }}</label>
	<div class="col-sm-8">
		{!! Form::text('datafim', null, ['class' => 'form-control datetime', 'id' => 'datafim', 'required']) !!}
		<small class="form-text text-muted">
		  	{{ __('content.desc-formato-datahora') }}
		</small>
	</div>
</div>
